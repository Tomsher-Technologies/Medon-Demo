<?php $__env->startSection('content'); ?>

<div class="card">
    <form class="" action="" id="sort_orders" method="GET">
        <div class="card-header row gutters-5">
            <div class="col-lg-12 mb-3">
                <h5 class="mb-md-0 h6">All Orders</h5>
            </div>

            <?php
                $shops = getActiveShops();
            ?>
            <div class="col-lg-3 ml-auto">
                <select id="shop_search" name="shop_search" class="form-control aiz-selectpicker" >
                    <option value="">Select Shop</option>
                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option <?php echo e(($shop_search == $shop->id) ? 'selected' : ''); ?> value="<?php echo e($shop->id); ?>"><?php echo e($shop->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-lg-2 ml-auto">
                <select class="form-control aiz-selectpicker" name="delivery_status" id="delivery_status">
                    <option value=""><?php echo e(translate('Filter by Delivery Status')); ?></option>
                    <option value="pending" <?php if($delivery_status == 'pending'): ?> selected <?php endif; ?>><?php echo e(translate('Pending')); ?></option>
                    <option value="confirmed" <?php if($delivery_status == 'confirmed'): ?> selected <?php endif; ?>><?php echo e(translate('Confirmed')); ?></option>
                    <option value="picked_up" <?php if($delivery_status == 'picked_up'): ?> selected <?php endif; ?>><?php echo e(translate('Picked Up')); ?></option>
                    <option value="partial_pick_up" <?php if($delivery_status == 'partial_pick_up'): ?> selected <?php endif; ?>><?php echo e(translate('Partial Pick Up')); ?></option>
                    <option value="partial_delivery" <?php if($delivery_status == 'partial_delivery'): ?> selected <?php endif; ?>><?php echo e(translate('Partial Delivery')); ?></option>
                    <option value="delivered" <?php if($delivery_status == 'delivered'): ?> selected <?php endif; ?>> <?php echo e(translate('Delivered')); ?></option>
                    <option value="cancelled" <?php if($delivery_status == 'cancelled'): ?> selected <?php endif; ?>><?php echo e(translate('Cancel')); ?></option>
                </select>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="aiz-date-range form-control" value="<?php echo e($date); ?>" name="date" placeholder="Filter by date" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?> placeholder="Type Order code & hit Enter">
                </div>
            </div>
            <div class="col-auto">
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-warning">Filter</button>
                    <a class="btn btn-info" href="<?php echo e(route('sales_report.index')); ?>" >Reset</a>
                    <a href="<?php echo e(route('export.sales_report')); ?>"  class="btn btn-danger" style="border-radius: 30px;">Excel Export</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        
                        <th>Order Code</th>
                        <th >Customer</th>
                        <th >Order Date</th>
                        <th  class="text-center" >No. of Products</th>
                        
                        <th >Amount</th>
                        <th >Shop</th>
                        <th  class="text-center">Delivery Status</th>
                        <!--<th   class="text-center">Payment Status</th>-->
                        <th class="text-center"><?php echo e(translate('options')); ?></th>
                    </tr>
                </thead>
                <tbody id="order-table">
                    <?php
                        $shops = getActiveShops();
                    ?>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                       
                    <tr>
                        <td>
                            <?php echo e(($key+1) + ($orders->currentPage() - 1)*$orders->perPage()); ?>

                        </td>
                       
                        <td>
                            <?php echo e($order->code); ?>

                        </td>
                        <td>
                            <?php if($order->user != null): ?>
                            <b>Name : </b><?php echo e($order->user->name); ?></br>
                            <b>Email : </b><?php echo e($order->user->email); ?></br>
                            <b>Phone : </b><?php echo e($order->user->phone); ?>

                            <?php else: ?>
                            Guest (<?php echo e($order->guest_id); ?>)
                            <?php endif; ?>
                        </td>
                         <td>
                            <?php echo e(date('d-m-Y h:i A', strtotime($order->created_at))); ?>

                        </td>
                        <td class="text-center">
                            <?php echo e(count($order->orderDetails)); ?>

                        </td>
                       
                        <td>
                            <?php echo e(single_price($order->grand_total)); ?>

                        </td>
                        <td>
                            <?php
                                $shopname = 'Not Assigned';
                                if($order->shop_id != null){
                                    $shopname = $order->shop->name;
                                }
                            ?>
                            
                            <?php echo e($shopname); ?>

                        </td>
                        <td class="text-center">
                            <?php
                                $status = $order->delivery_status;
                                if($order->delivery_status == 'cancelled') {
                                    $status = '<span class="badge badge-inline badge-danger">'.translate('Cancel').'</span>';
                                }

                            ?>
                            <?php echo ucfirst(str_replace('_', ' ', $status)); ?>

                        </td>
                        <!--<td class="text-center">-->
                        <!--    <?php if($order->payment_status == 'paid'): ?>-->
                        <!--    <span class="badge badge-inline badge-success"><?php echo e(translate('Paid')); ?></span>-->
                        <!--    <?php else: ?>-->
                        <!--    <span class="badge badge-inline badge-danger"><?php echo e(translate('Unpaid')); ?></span>-->
                        <!--    <?php endif; ?>-->
                        <!--</td>-->
                      
                        <td class="text-center">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('sales_orders.show', encrypt($order->id))); ?>" title="View">
                                <i class="las la-eye"></i>
                            </a>
                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="<?php echo e(route('invoice.download', $order->id)); ?>" title="Download Invoice">
                                <i class="las la-download"></i>
                            </a>
                            
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div class="aiz-pagination">
                <?php echo e($orders->appends(request()->input())->links()); ?>

            </div>

        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

        &.cancelled:before {
            background-color: #e756568a;
            border-color: #e51e1e8a;
            box-shadow: 0px 0px 4px 1px #a61d1d8a;
        }

        &.cancel_requested:before {
            background-color: #ffbebe7a;
            border-color: #e147477a;
            box-shadow: 0px 0px 4px 1px #ee64647a;
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/reports/sales.blade.php ENDPATH**/ ?>