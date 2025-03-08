<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Details</h1>
            <a class="btn btn-primary"
                href="<?php echo e(Session::has('last_url') ? Session::get('last_url') : route('all_orders.index')); ?>">Go Back</a>
        </div>
        <div class="card-body">
            <div class="row gutters-5 mb-3">
                
                <?php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                    $shops = getActiveShops();
                ?>

                
                <?php
                    if ($order->shop_id != '') {
                        $showModal = 1;
                    } else {
                        $showModal = 0;
                    }
                ?>

                <?php if($delivery_status == 'pending' || $delivery_status == 'confirmed'): ?>
                    <?php if($order->cancel_request == 0 || ($order->cancel_request == 1 && $order->cancel_approval == 2)): ?>
                        <div class="col-md-4">
                            <label for="update_payment_status"><b>Assign Shop</b></label>

                            <select id="shop_id" name="shop_id" class="form-control selectShopAssign" data-order="<?php echo e($order->id); ?>" data-status="<?php echo e($showModal); ?>">
                                <option value="" class="disabled-option">Select Shop</option>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($shop->id == old('shop_id', $order->shop_id)): ?> <?php echo e('selected'); ?> <?php endif; ?>
                                        value="<?php echo e($shop->id); ?>"><?php echo e($shop->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <div class="col-md-4"></div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="col-md-4"></div>
                <?php endif; ?>

                <div class="col-md-2">
                    <label for="update_payment_status"><b>Payment Status</b></label>
                    <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                        id="update_payment_status">
                        <option value="unpaid" <?php if($payment_status == 'unpaid'): ?> selected <?php endif; ?>>Unpaid
                        </option>
                        <option value="paid" <?php if($payment_status == 'paid'): ?> selected <?php endif; ?>>Paid
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="update_delivery_status"><b>Delivery Status</b></label>
                    <?php if($delivery_status != 'delivered' && $delivery_status != 'cancelled'): ?>
                        <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                            id="update_delivery_status">
                            <option value="pending" <?php if($delivery_status == 'pending'): ?> selected <?php endif; ?>>
                                Pending</option>
                            <option value="confirmed" <?php if($delivery_status == 'confirmed'): ?> selected <?php endif; ?>>
                                Confirmed</option>
                            <option value="partial_pick_up" <?php if($delivery_status == 'partial_pick_up'): ?> selected <?php endif; ?>>
                                Partial Pick Up</option>
                            <option value="picked_up" <?php if($delivery_status == 'picked_up'): ?> selected <?php endif; ?>>
                                Picked Up</option>
                            <option value="partial_delivery" <?php if($delivery_status == 'partial_delivery'): ?> selected <?php endif; ?> disabled>
                                Partial Delivery</option>
                            <option value="delivered" <?php if($delivery_status == 'delivered'): ?> selected <?php endif; ?> disabled>
                                Delivered</option>
                            <option value="cancelled" <?php if($delivery_status == 'cancelled'): ?> selected <?php endif; ?>>
                                Cancel</option>
                        </select>
                    <?php else: ?>
                        <input type="text" class="form-control" value="<?php echo e($delivery_status); ?>" disabled>
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <label for="update_estimated_date"><b>Estimated Delivery Date</b></label>
                    <input type="text" class="form-control" id="update_estimated_date"
                        value="<?php echo e($order->estimated_delivery != null ? date('d-m-Y', strtotime($order->estimated_delivery)) : ''); ?>"
                        <?php echo e($delivery_status == 'delivered' || $delivery_status == 'cancelled' ? 'disabled' : ''); ?>>
                </div>

                <div class="col-md-3 d-none">
                    <label for="update_tracking_code">Tracking Code (optional)</label>
                    <input type="text" class="form-control" id="update_tracking_code"
                        value="<?php echo e($order->tracking_code); ?>">
                </div>
            </div>
            <div class="mb-3">
                <?php echo QrCode::size(100)->generate($order->code); ?>

            </div>
            <div class="row gutters-5">
                <div class="col-sm-12 col-md-6 text-md-left">
                    <address>
                        <strong class="text-main"><?php echo e(json_decode($order->shipping_address)->name); ?></strong><br>
                        <?php echo e(json_decode($order->shipping_address)->email); ?><br>
                        <?php echo e(json_decode($order->shipping_address)->phone); ?><br>
                        <?php echo e(json_decode($order->shipping_address)->address); ?>,
                        <?php echo e(json_decode($order->shipping_address)->city); ?>

                        <?php echo e(json_decode($order->shipping_address)->state); ?>

                        <br>
                        <?php echo e(json_decode($order->shipping_address)->country); ?>

                    </address>
                    <p><b>Order Notes : </b> <?php echo e($order->order_notes ?? ''); ?></p>
                    <?php
                        $shopname = 'Not Assigned';
                        if ($order->shop_id != null) {
                            $shopname = $order->shop->name;
                        }
                    ?>
                    <p><b>Shop : </b> <?php echo e($shopname ?? ''); ?></p>
                    <?php if($order->manual_payment && is_array(json_decode($order->manual_payment_data, true))): ?>
                        <br>
                        <strong class="text-main">Payment Information</strong><br>
                        Name: <?php echo e(json_decode($order->manual_payment_data)->name); ?>,
                        Amount: <?php echo e(single_price(json_decode($order->manual_payment_data)->amount)); ?>,
                        TRX ID: <?php echo e(json_decode($order->manual_payment_data)->trx_id); ?>

                        <br>
                        <a href="<?php echo e(uploaded_asset(json_decode($order->manual_payment_data)->photo)); ?>"
                            target="_blank"><img
                                src="<?php echo e(uploaded_asset(json_decode($order->manual_payment_data)->photo)); ?>" alt=""
                                height="100"></a>
                    <?php endif; ?>
                </div>
                <div class="col-sm-12 col-md-6 float-right">
                    <table class="float-right">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">Order #</td>
                                <td class="text-right text-info text-bold"> <?php echo e($order->code); ?></td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Status</td>
                                <td class="text-right">
                                    <?php if($delivery_status == 'delivered'): ?>
                                        <span
                                            class="badge badge-inline badge-success"><?php echo e(translate(ucfirst(str_replace('_', ' ', $delivery_status)))); ?></span>
                                    <?php else: ?>
                                        <span
                                            class="badge badge-inline badge-info"><?php echo e(translate(ucfirst(str_replace('_', ' ', $delivery_status)))); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Order Date </td>
                                <td class="text-right"><?php echo e(date('d-m-Y h:i A', $order->date)); ?></td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    Total amount
                                </td>
                                <td class="text-right">
                                    <?php echo e(single_price($order->grand_total)); ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Payment method</td>
                                <td class="text-right">
                                    <?php echo e(translate(ucfirst(str_replace('_', ' ', $order->payment_type)))); ?></td>
                            </tr>
                            <?php if($order->payment_type == 'card' || $order->payment_type == 'card_wallet'): ?>
                                <tr>
                                    <td class="text-main text-bold">Payment Tracking Id</td>
                                    <td class="text-right">
                                        <?php echo e($order->payment_tracking_id); ?>

                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <ul class="status_indicator">
                <li class="status completed" style="float:left">Delivered</li>
                <li class="status picked_up ml-2" style="float:left">Picked Up</li>
            </ul>
            <br>
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table table-bordered aiz-table invoice-summary">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-all">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </th>
                                <th class="min-col">#</th>
                                <th width="10%">Photo</th>
                                <th class="text-uppercase">Description</th>
                                
                                <th class="min-col text-center text-uppercase">Qty
                                </th>
                                <th class="min-col text-center text-uppercase">
                                    Price</th>
                                <th class="min-col text-center text-uppercase">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $orderDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $statusColor = '#fff';
                                    if ($orderDetail->delivery_status == 'picked_up') {
                                        $statusColor = '#e9ae004f';
                                    } elseif ($orderDetail->delivery_status == 'delivered') {
                                        $statusColor = '#03ff0338';
                                    }

                                ?>
                                <tr style="background:<?php echo e($statusColor); ?>">
                                    <td>
                                        <?php if($orderDetail->delivery_status == 'pending' || $orderDetail->delivery_status == 'confirmed'): ?>
                                            <div class="form-group">
                                                <div class="aiz-checkbox-inline">
                                                    <label class="aiz-checkbox">
                                                        <input type="checkbox" class="check-one" name="id[]"
                                                            value="<?php echo e($orderDetail->id); ?>">
                                                        <span class="aiz-square-check"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td>
                                        <?php if($orderDetail->product != null): ?>
                                            <img height="50"
                                                src="<?php echo e(get_product_image($orderDetail->product->thumbnail_img, '300')); ?>">
                                        <?php else: ?>
                                            <strong>N/A</strong>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($orderDetail->product != null): ?>
                                            <strong class="text-muted"><?php echo e($orderDetail->product->name); ?></strong>
                                            

                                            
                                        <?php else: ?>
                                            <strong>Product Unavailable</strong>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?php echo e($orderDetail->quantity); ?></td>
                                    <td class="text-center">
                                        <?php if($orderDetail->og_price != $orderDetail->offer_price): ?>
                                            <del><?php echo e(single_price($orderDetail->og_price)); ?></del> <br>
                                        <?php endif; ?>
                                        <?php echo e(single_price($orderDetail->price / $orderDetail->quantity)); ?>

                                    </td>
                                    <td class="text-center"><?php echo e(single_price($orderDetail->price)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">Sub Total :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->orderDetails->sum('price'))); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Tax :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->orderDetails->sum('tax'))); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Shipping :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->shipping_cost)); ?>

                            </td>
                        </tr>
                        <?php if($order->coupon_discount): ?>
                            <tr>
                                <td>
                                    <strong class="text-muted">Coupon Discount :</strong>
                                </td>
                                <td>
                                    <?php echo e(single_price($order->coupon_discount)); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong class="text-muted">Coupon Code :</strong>
                                </td>
                                <td>
                                    <?php echo e($order->coupon_code); ?>

                                </td>
                            </tr>
                        <?php endif; ?>
                        

                        <tr>
                            <td>
                                <strong class="text-muted">TOTAL :</strong>
                            </td>
                            <td class="text-muted h5">
                                <?php echo e(single_price($order->grand_total)); ?>

                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right no-print">
                    <a href="<?php echo e(route('invoice.download', $order->id)); ?>" type="button"
                        class="btn btn-icon btn-light"><i class="las la-download"></i></a>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Shop Assign Details</h1>
        </div>
        <div class="card-body">
            <div class="col-lg-12 table-responsive">
                <table class="table table-bordered aiz-table invoice-summary">
                    <thead>
                        <tr class="bg-trans-dark">
                            <th data-breakpoints="lg" class="min-col">#</th>
                            <th>Assigned By</th>
                            <th>From Shop</th>
                            <th>To Shop</th>
                            <th>Reason</th>
                            <th>Transfer Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $assignHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <?php echo e($history->transferredBy->name ?? 'Admin'); ?>

                                    <?php if($history->transferredBy->user_type == 'admin'): ?>
                                        <span class="badge badge-info" style="width: 30%">Admin</span>
                                    <?php else: ?>
                                        <span class="badge badge-success" style="width: 25%">Shop</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($history->fromShop ? $history->fromShop->name : 'N/A'); ?></td>
                                <td><?php echo e($history->toShop->name); ?></td>
                                <td><?php echo e($history->reason); ?></td>
                                <td><?php echo e($history->created_at->format('d-m-Y H:i a')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Delivery Details</h1>
        </div>
        <div class="card-body">
            <?php
                $delivery = getOrderDeliveryDetails($order->id);
                // echo '<pre>';
                // print_r($delivery);
                // die;
            ?>
            <div class="col-lg-12 table-responsive">
                <table class="table table-bordered aiz-table invoice-summary">
                    <thead>
                        <tr class="bg-trans-dark">
                            <th data-breakpoints="lg" class="min-col">#</th>
                            <th width="20%">Delivery Boy</th>
                            <th class="text-uppercase  text-center">Assigned Date</th>
                            <th class="text-uppercase  text-center">delivery Status</th>
                            <th class="min-col text-center text-uppercase">payment status</th>
                            <th width="25%" class="min-col text-left text-uppercase">
                                delivery note</th>
                            <th class="min-col text-center text-uppercase">
                                delivery image</th>
                            <th class="min-col text-center text-uppercase">
                                delivery date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $delivery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delAs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <?php echo e(ucwords($delAs['deliveryBoy']['name'] ?? '')); ?>

                                </td>
                                <td class="text-center">
                                    <?php echo e($delAs['created_at'] != null ? date('d-M-Y H:i a', strtotime($delAs['created_at'])) : ''); ?>

                                </td>
                                <td class="text-capitalize text-center">
                                    <?php echo e($delAs['status'] == 1 ? 'Delivered' : 'Pending'); ?>

                                </td>
                                <td class="text-capitalize text-center">
                                    <?php echo e($delAs['payment_status'] == 1 ? 'Paid' : ''); ?>

                                </td>
                                <td class="text-left">
                                    <?php echo e($delAs['delivery_note'] ?? ''); ?>

                                </td>
                                <td class="text-center">
                                    <?php if(!empty($delAs['delivery_image'])): ?>
                                        <a href="<?php echo e(asset($delAs['delivery_image'])); ?>" target="_blank"><img
                                                src="<?php echo e(asset($delAs['delivery_image'])); ?>" width="150px"
                                                alt="Order Image" /></a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo e($delAs['delivery_date'] != null ? date('d-M-Y H:i a', strtotime($delAs['delivery_date'])) : ''); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transfer Modal -->
    <div class="modal fade" id="transferOrderModal" tabindex="-1" aria-labelledby="transferOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="transferOrderForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="transfer_order_id" id="transfer_order_id" value="<?php echo e($order->id); ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transferOrderModalLabel">Transfer Order to Another Shop</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <!-- Select Shop -->
                        <div class="mb-3">
                            <input type="hidden" name="to_shop_id" id="to_shop_id" class="form-select">
                        </div>

                        <!-- Reason -->
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Transfer</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Enter reason"></textarea>
                        </div>

                        <!-- Error Message -->
                        <div id="transferError" class="alert alert-danger d-none"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="cancelButton" style="border-radius: 50px;">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitTransfer">
                            <span id="submitTransferText">Transfer</span>
                            <span id="submitTransferSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        .status_indicator {
            margin: 0px 0px 20px;
            padding: 0;
            list-style: none;
        }

        .selectShopAssign .disabled-option {
            pointer-events: none; /* Prevent clicking on this option */
            color: #ccc; /* Change the color to look visually disabled */
        }

        .status {
            &.completed:before {
                background-color: #03ff0338;
                border-color: #78D965;
                box-shadow: 0px 0px 4px 1px #94E185;
            }

            &.picked_up:before {
                background-color: #e9ae004f;
                border-color: #FFB161;
                box-shadow: 0px 0px 4px 1px #FFC182;
            }

            &:before {
                content: ' ';
                display: inline-block;
                width: 25px;
                height: 12px;
                margin-right: 10px;
                border: 1px solid #000;
            }
        }
    </style>

    <link rel="stylesheet" href="<?php echo e(static_asset('assets/css/bootstrap-datepicker.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(static_asset('assets/js/bootstrap-datepicker.js')); ?>"></script>
    <script src="<?php echo e(static_asset('assets/js/sweetalert.min.js')); ?>"></script>

    <script type="text/javascript">
        $("#update_estimated_date").datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true,
        });

        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        $('#update_delivery_status').on('change', function() {
            var order_id = <?php echo e($order->id); ?>;
            var status = $('#update_delivery_status').val();
            let product_ids = [];
            $('.check-one:checkbox').each(function() {
                if (this.checked) {
                    product_ids.push($(this).val());
                }
            });

            if ((status == 'partial_pick_up' || status == 'partial_delivery') && product_ids.length === 0) {
                AIZ.plugins.notify('warning', 'Please select products');
                if (status == 'partial_pick_up') {
                    $('#update_delivery_status').val('confirmed').selectpicker('refresh');
                }
                if (status == 'partial_delivery') {
                    $('#update_delivery_status').val('partial_pick_up').selectpicker('refresh');
                }
                return false;
            }

            $.post('<?php echo e(route('orders.update_delivery_status')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                order_id: order_id,
                status: status,
                product_ids: product_ids
            }, function(data) {
                if (data == 0) {
                    AIZ.plugins.notify('danger', 'Order already delivered');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);

                } else {
                    AIZ.plugins.notify('success', 'Delivery status has been updated');
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                }

            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = <?php echo e($order->id); ?>;
            var status = $('#update_payment_status').val();
            $.post('<?php echo e(route('orders.update_payment_status')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', 'Payment status has been updated');
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            });
        });

        $('#update_estimated_date').on('change', function() {
            var order_id = <?php echo e($order->id); ?>;
            var deliveryDate = $('#update_estimated_date').val();

            swal({
                    title: "Are you sure?",
                    // text: msg,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.post('<?php echo e(route('orders.update_estimated_date')); ?>', {
                            _token: '<?php echo e(@csrf_token()); ?>',
                            order_id: order_id,
                            deliveryDate: deliveryDate
                        }, function(data) {
                            AIZ.plugins.notify('success', 'Estimated delivery date has been updated');
                            setTimeout(function() {
                                window.location.reload();
                            }, 3000);
                        });
                    } else {
                        window.location.reload();
                    }
                });
        });

        $('#update_tracking_code').on('change', function() {
            var order_id = <?php echo e($order->id); ?>;
            var tracking_code = $('#update_tracking_code').val();
            $.post('<?php echo e(route('orders.update_tracking_code')); ?>', {
                _token: '<?php echo e(@csrf_token()); ?>',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', 'Order tracking code has been updated');
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            });
        });

        $('#cancelButton').on('click', function() {
            location.reload(); // Reload the page
        });

        $(document).on('change', '.selectShopAssign', function() {
            var shop_id = $(this).val();
            var order_id = $(this).attr('data-order');
            var status = $(this).attr('data-status');
            
            if (status == 1 && shop_id != '') {
                $('#to_shop_id').val(shop_id);
                $('#transferOrderModal').modal('show');
            } else {
                if (shop_id != ''){
                    assignShop(order_id, shop_id, null);
                }else{
                    swal("Please select a shop!", { icon: "warning" });
                }
            }
        });

        $('#transferOrderForm').on('submit', function(e) {
            e.preventDefault();

            var transfer_order_id = $('#transfer_order_id').val();
            var to_shop_id = $('#to_shop_id').val();
            var transfer_reason = $('#reason').val().trim();

            if (transfer_reason === '') {
                $('#transferError').removeClass('d-none').text('Please enter a reason for the transfer.');
                return; // Stop the form from submitting
            }

          
            $('#transferError').addClass('d-none').text('');
            assignShop(transfer_order_id, to_shop_id, transfer_reason);

        });


        function assignShop(order_id, shop_id, reason){
            swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $('#submitTransfer').prop('disabled', true);
                    $('#submitTransferSpinner').removeClass('d-none');
                    $('#submitTransferText').text('Transferring...');
                    $.ajax({
                        url: "<?php echo e(route('assign-shop-order')); ?>",
                        type: "POST",
                        data: {
                            order_id: order_id,
                            shop_id: shop_id,
                            reason: reason,
                            _token: '<?php echo e(@csrf_token()); ?>',
                        },
                        dataType: "html",
                        success: function(response) {
                            swal("Successfully updated!", {
                                icon: "success",
                            });
                            if(response == 1){
                                setTimeout(function() {
                                    window.location.href= '<?php echo e(route("all_orders.index")); ?>';
                                }, 3000);
                            }else{
                                setTimeout(function() {
                                    window.location.reload();
                                }, 3000);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            $('#submitTransfer').prop('disabled', false);
                            $('#submitTransferSpinner').addClass('d-none');
                            $('#submitTransferText').text('Transfer');

                            swal("Something went wrong!", {
                                icon: "warning",
                            });
                        }
                    });
                } else {
                    $(this).val('');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/sales/all_orders/show.blade.php ENDPATH**/ ?>