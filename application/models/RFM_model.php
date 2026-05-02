<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RFM_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Aggregates RFM from ap_invoice_number + ap_customer.
     * total_netto = (total + surcharge) - (diskon + diskon_free + diskon_otomatis + poin_value)
     *
     * @param string|null $id_store ap_store.id_store (ap_invoice_number.id_toko)
     * @param array|null $date_range ['start'=>'Y-m-d', 'end'=>'Y-m-d']
     * @param int|null $id_group ap_customer_group.id_group (ap_customer.kategori)
     * @return array of objects: id_customer, name, recency_days, frequency, monetary, r_score, f_score, m_score, total_rfm_score, segment_name
     */
    public function get_rfm_data($id_store = null, $date_range = null, $id_group = null) {
        $total_netto_expr = "( inv.total + COALESCE(inv.surcharge,0) - COALESCE(inv.diskon,0) - COALESCE(inv.diskon_free,0) - COALESCE(inv.diskon_otomatis,0) - COALESCE(inv.poin_value,0) )";
        $sql = "
            SELECT
                c.id_customer,
                c.nama AS name,
                c.kategori AS id_group,
                DATEDIFF(CURDATE(), MAX(inv.tanggal)) AS recency_days,
                COUNT(inv.no_invoice) AS frequency,
                SUM(" . $total_netto_expr . ") AS monetary
            FROM ap_customer c
            INNER JOIN ap_invoice_number inv ON inv.id_customer = c.id_customer
            WHERE 1=1
        ";
        $binds = [];
        if (!empty($id_store)) {
            $sql .= " AND inv.id_toko = ?";
            $binds[] = $id_store;
        }
        if (!empty($date_range['start']) && !empty($date_range['end'])) {
            $sql .= " AND DATE(inv.tanggal) BETWEEN ? AND ?";
            $binds[] = $date_range['start'];
            $binds[] = $date_range['end'];
        }
        if ($id_group !== null && $id_group !== '') {
            $sql .= " AND c.kategori = ?";
            $binds[] = $id_group;
        }
        $sql .= " GROUP BY c.id_customer, c.nama, c.kategori HAVING monetary > 0 ORDER BY c.id_customer";

        $rows = empty($binds) ? $this->db->query($sql)->result() : $this->db->query($sql, $binds)->result();
        if (empty($rows)) {
            return [];
        }

        $recency = [];
        $frequency = [];
        $monetary = [];
        foreach ($rows as $r) {
            $recency[] = (int) $r->recency_days;
            $frequency[] = (int) $r->frequency;
            $monetary[] = (float) $r->monetary;
        }
        $r_quintiles = $this->_quintile_bounds($recency, false);
        $f_quintiles = $this->_quintile_bounds($frequency, true);
        $m_quintiles = $this->_quintile_bounds($monetary, true);

        $out = [];
        foreach ($rows as $row) {
            $rd = (int) $row->recency_days;
            $fd = (int) $row->frequency;
            $md = (float) $row->monetary;
            $r_score = $this->_score($rd, $r_quintiles, false);
            $f_score = $this->_score($fd, $f_quintiles, true);
            $m_score = $this->_score($md, $m_quintiles, true);
            $total_rfm_score = $r_score + $f_score + $m_score;
            $segment_name = $this->_get_segment($r_score, $f_score, $m_score);
            $out[] = (object) [
                'id_customer'    => $row->id_customer,
                'name'           => $row->name,
                'id_group'       => $row->id_group,
                'recency_days'   => $rd,
                'frequency'      => $fd,
                'monetary'       => $md,
                'r_score'        => $r_score,
                'f_score'        => $f_score,
                'm_score'        => $m_score,
                'total_rfm_score'=> $total_rfm_score,
                'segment_name'   => $segment_name,
            ];
        }
        return $out;
    }

    /**
     * Quintile bounds (0-4 indices for 5 buckets). ascending=false: lower value = higher score (e.g. recency).
     */
    private function _quintile_bounds($values, $ascending) {
        $arr = $values;
        $n = count($arr);
        if ($n === 0) return [];
        $arr = array_values($arr);
        sort($arr, $ascending ? SORT_NUMERIC : SORT_NUMERIC);
        $q = [];
        for ($i = 1; $i <= 4; $i++) {
            $idx = (int) floor($n * $i / 5);
            if ($idx >= $n) $idx = $n - 1;
            $q[$i] = $arr[$idx];
        }
        return $q;
    }

    private function _score($value, $quintiles, $higher_better) {
        if ($higher_better) {
            if ($value >= $quintiles[4]) return 5;
            if ($value >= $quintiles[3]) return 4;
            if ($value >= $quintiles[2]) return 3;
            if ($value >= $quintiles[1]) return 2;
            return 1;
        }
        if ($value <= $quintiles[1]) return 5;
        if ($value <= $quintiles[2]) return 4;
        if ($value <= $quintiles[3]) return 3;
        if ($value <= $quintiles[4]) return 2;
        return 1;
    }

    /**
     * Segment by R/F/M scores (1-5). R: 5=most recent.
     */
    private function _get_segment($r, $f, $m) {
        if ($r >= 4 && $f >= 4 && $m >= 4) return 'Champions';
        if ($r >= 4 && $f >= 1 && $f <= 3 && $m >= 1 && $m <= 3) return 'Potential Loyalist';
        if ($r >= 2 && $r <= 3 && $f >= 3 && $m >= 1 && $m <= 3) return 'Loyal Customers';
        if ($r >= 2 && $r <= 3 && $f >= 1 && $f <= 2 && $m >= 1 && $m <= 3) return 'About to Sleep';
        if ($r <= 1 && $f >= 4 && $m >= 4) return "Can't Lose Them";
        if ($r >= 2 && $r <= 3 && $f >= 1 && $m >= 4) return 'At Risk';
        if ($r <= 2 && $f <= 2) return 'Hibernating';
        return 'Others';
    }

    public function get_customer_groups() {
        return $this->db->get('ap_customer_group')->result();
    }

    public function get_stores() {
        return $this->db->get('ap_store')->result();
    }

    /** Alias for get_rfm_data with same signature. */
    public function calculate_rfm($id_store = null, $date_range = null, $id_group = null) {
        return $this->get_rfm_data($id_store, $date_range, $id_group);
    }
}
