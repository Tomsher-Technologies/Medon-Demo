<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Details</h1>
            <a class="btn btn-primary" href="<?php echo e(Session::has('last_url') ? Session::get('last_url') : route('cancel_requests.index')); ?>" >Go Back</a>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                   
                </div>
                <?php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                ?>
                <div class="col-md-4 text-right">
                    Estimated Delivery Date :  <b><?php echo ($order->estimated_delivery != NULL && $order->estimated_delivery != '0000-00-00') ? date('d-m-Y', strtotime($order->estimated_delivery)) : ''; ?></b>
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
                        if($order->shop_id != null){
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
                                
                                <th class="min-col">#</th>
                                <th width="10%">Photo</th>
                                <th class="text-uppercase">Description</th>
                                
                                <th  class="min-col text-center text-uppercase">Qty
                                </th>
                                <th class="min-col text-center text-uppercase">
                                    Price</th>
                                <th  class="min-col text-center text-uppercase">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $orderDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $statusColor = '#fff';
                                    if ($orderDetail->delivery_status == 'picked_up'){
                                        $statusColor = '#e9ae004f';
                                    }elseif ($orderDetail->delivery_status == 'delivered') {
                                        $statusColor = '#03ff0338';
                                    }
                                    
                                ?>
                                <tr style="background:<?php echo e($statusColor); ?>">
                                   
                                    <td><?php echo e($key + 1); ?></td>
                                    <td>
                                        <?php if($orderDetail->product != null): ?>
                                            <img height="50" src="<?php echo e(get_product_image($orderDetail->product->thumbnail_img, '300')); ?>">
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
                        <tr>
                            <td>
                                <strong class="text-muted">Coupon :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->coupon_discount)); ?>

                            </td>
                        </tr>
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

    
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
    .status_indicator {
        margin: 0px 0px 20px;
        padding: 0;
        list-style: none;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
       
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/sales/cancel_orders_show.blade.php ENDPATH**/ ?>