<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
                <i class="fa fa-users"></i> RFM Analysis (Recency, Frequency, Monetary)
            </h3>
        </div>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-filter"></i> Filter</h4>
                </div>
                <div class="panel-body">
                    <form id="filterForm" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_toko">Store</label>
                                    <select class="select2" id="id_toko" name="id_toko" style="width: 100%;">
                                        <?php if (!empty($isAdmin)) { ?>
                                            <option value="">-- Semua Store --</option>
                                        <?php } ?>
                                        <?php foreach ($toko as $tk) { ?>
                                            <option value="<?php echo $tk->id_store; ?>" <?php if (isset($tk->id_store) && $tk->id_store == $id_toko) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($tk->store); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="id_group">Customer Group</label>
                                    <select class="select2" id="id_group" name="id_group" style="width: 100%;">
                                        <option value="">-- Semua --</option>
                                        <?php if (!empty($group_customer)) foreach ($group_customer as $gc) { ?>
                                            <option value="<?php echo $gc->id_group; ?>" <?php if (isset($id_group) && $id_group == $gc->id_group) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($gc->group_customer); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="segment_name">Segment</label>
                                    <select class="select2" id="segment_name" name="segment_name" style="width: 100%;">
                                        <option value="">-- Semua --</option>
                                        <option value="Champions" <?php if (isset($segment_name) && $segment_name === 'Champions') echo 'selected'; ?>>Champions</option>
                                        <option value="Loyal Customers" <?php if (isset($segment_name) && $segment_name === 'Loyal Customers') echo 'selected'; ?>>Loyal Customers</option>
                                        <option value="Potential Loyalist" <?php if (isset($segment_name) && $segment_name === 'Potential Loyalist') echo 'selected'; ?>>Potential Loyalist</option>
                                        <option value="At Risk" <?php if (isset($segment_name) && $segment_name === 'At Risk') echo 'selected'; ?>>At Risk</option>
                                        <option value="Can't Lose Them" <?php if (isset($segment_name) && $segment_name === "Can't Lose Them") echo 'selected'; ?>>Can't Lose Them</option>
                                        <option value="Hibernating" <?php if (isset($segment_name) && $segment_name === 'Hibernating') echo 'selected'; ?>>Hibernating</option>
                                        <option value="About to Sleep" <?php if (isset($segment_name) && $segment_name === 'About to Sleep') echo 'selected'; ?>>About to Sleep</option>
                                        <option value="Others" <?php if (isset($segment_name) && $segment_name === 'Others') echo 'selected'; ?>>Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="text" class="form-control datepicker" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo htmlspecialchars($tanggal_mulai); ?>" style="width: 100%;">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai</label>
                                    <input type="text" class="form-control datepicker" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo htmlspecialchars($tanggal_selesai); ?>" style="width: 100%;">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <label>&nbsp;</label><br>
                                <button type="button" class="btn btn-primary" id="btnFilter"><i class="fa fa-search"></i> </button>
                                <button type="button" class="btn btn-success" id="btnExport"><i class="fa fa-download"></i> Export</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="recency_min">Recency Min (hari)</label>
                                    <input type="number" class="form-control" id="recency_min" name="recency_min" value="<?php echo htmlspecialchars(isset($recency_min) ? $recency_min : ''); ?>" placeholder="" min="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="recency_max">Recency Max (hari)</label>
                                    <input type="number" class="form-control" id="recency_max" name="recency_max" value="<?php echo htmlspecialchars(isset($recency_max) ? $recency_max : ''); ?>" placeholder="" min="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="frequency_min">Frequency Min</label>
                                    <input type="number" class="form-control" id="frequency_min" name="frequency_min" value="<?php echo htmlspecialchars(isset($frequency_min) ? $frequency_min : ''); ?>" placeholder="" min="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="frequency_max">Frequency Max</label>
                                    <input type="number" class="form-control" id="frequency_max" name="frequency_max" value="<?php echo htmlspecialchars(isset($frequency_max) ? $frequency_max : ''); ?>" placeholder="" min="0">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="monetary_min">Monetary Min</label>
                                    <input type="text" class="form-control" id="monetary_min" name="monetary_min" value="<?php echo htmlspecialchars(isset($monetary_min) ? $monetary_min : ''); ?>" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="monetary_max">Monetary Max</label>
                                    <input type="text" class="form-control" id="monetary_max" name="monetary_max" value="<?php echo htmlspecialchars(isset($monetary_max) ? $monetary_max : ''); ?>" placeholder="">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="rfm-segment-stats"></div>

    <div class="row">
        <div class="col-lg-12">
            <div class="portlet">
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase"><i class="fa fa-table"></i> Daftar Customer RFM</h3>
                    <div class="portlet-widgets">
                        <a data-toggle="collapse" data-parent="#accordion1" href="#rfmTableCollapse"><i class="ion-minus-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="rfmTableCollapse" class="panel-collapse collapse in">
                    <div class="portlet-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped" id="rfmTable">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th class="text-right">Recency (hari)</th>
                                        <th class="text-right">Frequency</th>
                                        <th class="text-right">Monetary</th>
                                        <th class="text-center">R</th>
                                        <th class="text-center">F</th>
                                        <th class="text-center">M</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Segment</th>
                                    </tr>
                                </thead>
                                <tbody id="rfmTableBody">
                                    <tr><td colspan="10" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
