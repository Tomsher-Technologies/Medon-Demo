<?php $__env->startSection('content'); ?>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Details</h1>
            <a class="btn btn-primary" href="<?php echo e(Session::has('last_url') ? Session::get('last_url') : route('return_requests.index')); ?>" >Go Back</a>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-center text-md-left">
                </div>
                <?php
                    $delivery_status = $order->order->delivery_status;
                    $payment_status = $order->order->payment_status;
                ?>
            </div>
            <div class="mb-3">
                <?php echo QrCode::size(100)->generate($order->order->code); ?>

            </div>
            <div class="row gutters-5">
                <div class="col-sm-12 col-md-6 text-md-left">
                    <address>
                        <strong class="text-main"><?php echo e(json_decode($order->order->shipping_address)->name); ?></strong><br>
                        <?php echo e(json_decode($order->order->shipping_address)->email); ?><br>
                        <?php echo e(json_decode($order->order->shipping_address)->phone); ?><br>
                        <?php echo e(json_decode($order->order->shipping_address)->address); ?>,
                        <?php echo e(json_decode($order->order->shipping_address)->city); ?>

                        <?php echo e(json_decode($order->order->shipping_address)->state); ?>

                        <br>
                        <?php echo e(json_decode($order->order->shipping_address)->country); ?>

                    </address>
                    <p><b>Order Notes : </b> <?php echo e($order->order_notes ?? ''); ?></p>
                     <?php
                        $shopname = 'Not Assigned';
                        if($order->order->shop_id != null){
                            $shopname = $order->order->shop->name;
                        }
                    ?>
                    <p><b>Shop : </b> <?php echo e($shopname ?? ''); ?></p>
                </div>
                <div class="col-sm-12 col-md-6 float-right">
                    <table class="float-right">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">Order #</td>
                                <td class="text-right text-info text-bold"> <?php echo e($order->order->code); ?></td>
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
                                <td class="text-right"><?php echo e(date('d-m-Y h:i A', $order->order->date)); ?></td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    Total amount
                                </td>
                                <td class="text-right">
                                    <?php echo e(single_price($order->order->grand_total)); ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">Payment method</td>
                                <td class="text-right">
                                    <?php echo e(translate(ucfirst(str_replace('_', ' ', $order->order->payment_type)))); ?></td>
                            </tr>
                            <?php if($order->order->payment_type == 'card' || $order->order->payment_type == 'card_wallet'): ?>
                                <tr>
                                    <td class="text-main text-bold">Payment Tracking Id</td>
                                    <td class="text-right">
                                        <?php echo e($order->order->payment_tracking_id); ?>

                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <ul class="status_indicator">
                
                <li class="status picked_up ml-2" style="float:left">Returned</li>
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
                                
                                <th class="min-col text-center text-uppercase">Qty
                                </th>
                                <th class="min-col text-center text-uppercase">
                                    Price</th>
                                <th class="min-col text-center text-uppercase">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $order->order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $orderDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $statusColor = '#fff';
                                    if ($order->product_id == $orderDetail->product_id){
                                        $statusColor = '#e9ae004f';
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
                                <?php echo e(single_price($order->order->orderDetails->sum('price'))); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Tax :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->order->orderDetails->sum('tax'))); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Shipping :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->order->shipping_cost)); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Coupon :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->order->coupon_discount)); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">Offer Discount :</strong>
                            </td>
                            <td>
                                <?php echo e(single_price($order->offer_discount)); ?>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">TOTAL :</strong>
                            </td>
                            <td class="text-muted h5">
                                <?php echo e(single_price($order->order->grand_total)); ?>

                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right no-print">
                    <a href="<?php echo e(route('invoice.download', $order->order->id)); ?>" type="button"
                        class="btn btn-icon btn-light"><i class="las la-download"></i></a>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Delivery Details</h1>
        </div>
        <div class="card-body">
            <?php
                $delivery = getOrderDeliveryDetails($order->order->id);
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
                                    <?php echo e(($delAs['created_at'] != null) ? date('d-M-Y H:i a', strtotime($delAs['created_at'])) : ''); ?>

                                </td>    
                                <td class="text-capitalize text-center">
                                    <?php echo e(($delAs['status'] == 1) ? 'Delivered' : 'Pending'); ?>

                                </td>
                                <td class="text-capitalize text-center">
                                    <?php echo e(($delAs['payment_status'] == 1) ? 'Paid' : ''); ?>

                                </td>
                                <td class="text-left">
                                    <?php echo e($delAs['delivery_note'] ?? ''); ?>

                                </td>
                                <td class="text-center">
                                    <?php if(!empty($delAs['delivery_image'])): ?>
                                        <a href="<?php echo e(asset($delAs['delivery_image'])); ?>" target="_blank"><img src="<?php echo e(asset($delAs['delivery_image'])); ?>" width="150px" alt="Order Image"/></a>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo e(($delAs['delivery_date'] != null) ? date('d-M-Y H:i a', strtotime($delAs['delivery_date'])) : ''); ?>

                                </td>             
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">Order Return Delivery Details</h1>
        </div>
        <div class="card-body">
            <div class="col-lg-12 table-responsive">
                <table class="table table-bordered aiz-table invoice-summary">
                    <thead>
                        <tr class="bg-trans-dark">
                            <th data-breakpoints="lg" class="min-col">#</th>
                            <th width="20%">Delivery Boy</th>
                            <th class="text-uppercase  text-center">Assigned Date</th>
                            <th class="text-uppercase  text-center">delivery Status</th>
                            <th width="25%" class="min-col text-left text-uppercase">
                                delivery note</th>
                            <th class="min-col text-center text-uppercase">
                                delivery image</th>
                            <th class="min-col text-center text-uppercase">
                                    delivery date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <?php echo e(ucwords($order->deliveryBoy->name ?? '')); ?>

                            </td>
                            <td class="text-center">
                                <?php echo e(($order->delivery_assigned_date != null) ? date('d-M-Y H:i a', strtotime($order->delivery_assigned_date)) : ''); ?>

                            </td>    
                            <td class="text-capitalize text-center">
                                <?php echo e(($order->delivery_status == 1) ? 'Delivered' : 'Pending'); ?>

                            </td>
                            
                            <td class="text-left">
                                <?php echo e($order->delivery_note ?? ''); ?>

                            </td>
                            <td class="text-center">
                                <?php if(!empty($order->delivery_image)): ?>
                                    <a href="<?php echo e(asset($order->delivery_image)); ?>" target="_blank"><img src="<?php echo e(asset($order->delivery_image)); ?>" width="150px" alt="Order Image"/></a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php echo e(($order->delivery_completed_date != null) ? date('d-M-Y H:i a', strtotime($order->delivery_completed_date)) : ''); ?>

                            </td>             
                        </tr>
                    </tbody>
                </table>
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/sales/return_orders_show.blade.php ENDPATH**/ ?>