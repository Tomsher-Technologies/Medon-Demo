<?php $__env->startSection('content'); ?>

<div class="card">
    <form class="" action="" id="sort_orders" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">All Orders</h5>
            </div>

            

            <!-- Change Status Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                <?php echo e(translate('Choose an order status')); ?>

                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <select class="form-control aiz-selectpicker" onchange="change_status()" data-minimum-results-for-search="Infinity" id="update_delivery_status">
                                <option value="pending"><?php echo e(translate('Pending')); ?></option>
                                <option value="confirmed"><?php echo e(translate('Confirmed')); ?></option>
                                <option value="picked_up"><?php echo e(translate('Picked Up')); ?></option>
                                <option value="on_the_way"><?php echo e(translate('On The Way')); ?></option>
                                <option value="delivered"><?php echo e(translate('Delivered')); ?></option>
                                <option value="cancelled"><?php echo e(translate('Cancel')); ?></option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
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
                    <option value="delivered" <?php if($delivery_status == 'delivered'): ?> selected <?php endif; ?>> *-<?php echo e(translate('Delivered')); ?></option>
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
                </div>
            </div>
        </div>

        <div class="card-body">
            <ul class="status_indicator">
                <li class="status completed" style="float:left">Delivered</li>
                <li class="status picked_up ml-2" style="float:left">Partial Delivery</li>
                <li class="status cancel_requested ml-2" style="float:left">Cancel Requested</li>
                <li class="status cancelled ml-2" style="float:left">Cancelled</li>
            </ul>
            <br>
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        
                        <th>Order Code</th>
                        <th  class="text-center" >No. of Products</th>
                        <th >Customer</th>
                        <th >Amount</th>
                        <th  class="text-center">Delivery Status</th>
                        <th   class="text-center">Payment Status</th>
                        <?php if(addon_is_activated('refund_request')): ?>
                        <th>Refund</th>
                        <?php endif; ?>

                        <?php if(Auth::user()->shop_id != NULL && Auth::user()->user_type == 'staff'): ?>
                            <th class="text-center"  width="20%">
                                <?php echo e(translate('Assign Delivery Boy')); ?>

                            </th>
                        <?php else: ?>
                            <th class="text-center" width="25%">
                                <?php echo e(translate('Assign Store')); ?>

                            </th>
                        <?php endif; ?>
                        
                        <th class="text-center"><?php echo e(translate('options')); ?></th>
                    </tr>
                </thead>
                <tbody id="order-table">
                    
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $statusColor = '#fff';
                            if ($order->delivery_status == 'partial_delivery'){
                                $statusColor = '#e9ae004f';
                            }elseif ($order->delivery_status == 'delivered') {
                                $statusColor = '#03ff0338';
                            }elseif ($order->delivery_status == 'cancelled') {
                                $statusColor = '#fd79798a';
                            }elseif ($order->cancel_request == 1 && $order->cancel_approval == 0) {
                                $statusColor = '#ffbebe30';
                            }
                            
                        ?>
                    <tr style="background:<?php echo e($statusColor); ?>">
                        <td>
                            <?php echo e(($key+1) + ($orders->currentPage() - 1)*$orders->perPage()); ?>

                        </td>
                        
                        <td>
                            <?php echo e($order->code); ?>

                        </td>
                        <td class="text-center">
                            <?php echo e(count($order->orderDetails)); ?>

                        </td>
                        <td>
                            <?php if($order->user != null): ?>
                            <?php echo e($order->user->name); ?>

                            <?php else: ?>
                            Guest (<?php echo e($order->guest_id); ?>)
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo e(single_price($order->grand_total)); ?>

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
                        <td class="text-center">
                            <?php if($order->payment_status == 'paid'): ?>
                            <span class="badge badge-inline badge-success"><?php echo e(translate('Paid')); ?></span>
                            <?php else: ?>
                            <span class="badge badge-inline badge-danger"><?php echo e(translate('Unpaid')); ?></span>
                            <?php endif; ?>
                        </td>
                        <?php if(addon_is_activated('refund_request')): ?>
                        <td>
                            <?php if(count($order->refund_requests) > 0): ?>
                            <?php echo e(count($order->refund_requests)); ?> Refund
                            <?php else: ?>
                            No Refund
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <?php if(Auth::user()->shop_id != NULL && Auth::user()->user_type == 'staff'): ?>
                            <td class="text-center">
                                <?php if(!in_array($status,['pending','picked_up','delivered','cancelled']) && ($order->cancel_request == 0 || ($order->cancel_request == 1 && $order->cancel_approval == 2))): ?>
                                    <a href="<?php echo e(route('delivery-agents', encrypt($order->id))); ?>" class="btn btn-sm btn-success">Find Nearest Agent</a>
                                <?php endif; ?>

                                <?php if(in_array($status,['partial_pick_up','picked_up','confirmed','partial_delivery'])): ?>
                                    <?php
                                        $assignedTo = getAssignedDeliveryBoy($order->id);
                                    ?>
                                    <?php if($assignedTo != ''): ?>
                                        <br>Assigned to <b> <?php echo e($assignedTo); ?> </b>
                                    <?php endif; ?>
                                <?php elseif($status == 'delivered'): ?>
                                    <?php
                                        $deliveredBy = getDeliveryBoy($order->id);
                                    ?>
                                    <?php if(count($deliveredBy) > 0): ?>
                                        <br>Delivered by 
                                        <?php $__currentLoopData = $deliveredBy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $dby): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($k != 0): ?>
                                                ,
                                            <?php endif; ?>
                                            <b> <?php echo e($dby->deliveryBoy->name ?? ''); ?> </b>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        <?php else: ?>
                            <td class="myInputGroupSelect text-center">
                                <?php if($status == 'pending' || $status == 'confirmed'): ?>
                                    <?php
                                        if($order->shop_id != null){
                                            $color = 'border:2px solid #09c309';
                                            $showModal = 1;
                                        }else {
                                            $color = 'border:2px solid red';
                                            $showModal = 0;
                                        }
                                    ?>
                                    <?php if($order->cancel_request == 0 || ($order->cancel_request == 1 && $order->cancel_approval == 2)): ?>
                                        <select id="shop_id<?php echo e($key); ?>" name="shop_id<?php echo e($key); ?>" class="form-control selectShop selectShopAssign"  data-status="<?php echo e($showModal); ?>" data-order="<?php echo e($order->id); ?>" style="<?php echo e($color); ?>">
                                            <option value="">Select Shop</option>
                                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php if($shop->id == old('shop_id',$order->shop_id)): ?> <?php echo e('selected'); ?> <?php endif; ?> value="<?php echo e($shop->id); ?>"><?php echo e($shop->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <b><?php echo e($order->shop? $order->shop->name : 'N/A'); ?></b>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>

                        <td class="text-center">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('all_orders.show', encrypt($order->id))); ?>" title="View">
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

<!-- Transfer Modal -->
    <div class="modal fade" id="transferOrderModal" tabindex="-1" aria-labelledby="transferOrderModalLabel"
    aria-hidden="true">
        <div class="modal-dialog">
            <form id="transferOrderForm">
                <?php echo csrf_field(); ?>
                <input type="text" name="transfer_order_id" id="transfer_order_id" value="">
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
                            <input type="text" name="to_shop_id" id="to_shop_id" class="form-select">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">

        $('#cancelButton').on('click', function() {
            location.reload(); // Reload the page
        });

        $(document).on('change','.selectShop',function(){
            
            var shop_id = $(this).val();
            var order_id = $(this).attr('data-order');
            var status = $(this).attr('data-status');

            if (status == 1 && shop_id != '') {
                $('#transfer_order_id').val(order_id);
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

        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

//        function change_status() {
//            var data = new FormData($('#order_form')[0]);
//            $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
//                url: "<?php echo e(route('bulk-order-status')); ?>",
//                type: 'POST',
//                data: data,
//                cache: false,
//                contentType: false,
//                processData: false,
//                success: function (response) {
//                    if(response == 1) {
//                        location.reload();
//                    }
//                }
//            });
//        }

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "<?php echo e(route('bulk-order-delete')); ?>",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

     
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/sales/all_orders/index.blade.php ENDPATH**/ ?>