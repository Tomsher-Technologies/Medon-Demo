<?php $__env->startSection('content'); ?>

<div class="card">
    
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">All Return Requests</h5>
            </div>
        </div>

        <?php
            $shops = getActiveShops();
        ?>
        
        <div class="card-body">
            
                <form class="" action="" id="sort_orders" method="GET">
                    <div class="row">
                        
                        <div class="col-lg-3 mt-2">
                            <div class="form-group mb-2">
                                <label>Order Code</label>
                                <input type="text" class="form-control" id="search" name="search"<?php if(isset($search)): ?> value="<?php echo e($search); ?>" <?php endif; ?> placeholder="Type Order code & hit Enter">
                            </div>
                        </div>

                        <?php if(Auth::user()->shop_id != NULL && Auth::user()->user_type == 'staff'): ?>
                            <?php 
                                $shopAgents = getShopDeliveryAgents(Auth::user()->shop_id);
                            ?>
                            <div class="col-lg-3 mt-2">
                                <div class="form-group mb-2">
                                    <label>Delivery Boy</label>
                                    <select id="agent_search" name="agent_search" class="form-control" >
                                        <option value="">Select delivery boy</option>
                                        <?php $__currentLoopData = $shopAgents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(($agent_search == $agent['id']) ? 'selected' : ''); ?> value="<?php echo e($agent['id']); ?>"><?php echo e($agent['name']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 mt-2">
                                <div class="form-group mb-2">
                                    <label>Delivery Approval Status</label>
                                    <select id="da_search" name="da_search" class="form-control" >
                                        <option <?php echo e(($da_search == '') ? 'selected' : ''); ?> value="">Select status</option>
                                        <option <?php echo e(($da_search == '0') ? 'selected' : ''); ?> value="10">Pending</option>
                                        <option <?php echo e(($da_search == '1') ? 'selected' : ''); ?> value="1">Approved</option>
                                        <option <?php echo e(($da_search == '2') ? 'selected' : ''); ?> value="2">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-lg-3 mt-2">
                                <div class="form-group mb-2">
                                    <label>Request Date</label>
                                    <input type="text" class="aiz-date-range form-control" value="<?php echo e($date); ?>" name="date" placeholder="Filter by request date" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3 mt-2">
                                <div class="form-group mb-2">
                                    <label>Request Approval Status</label>
                                    <select id="ra_search" name="ra_search" class="form-control" >
                                        <option <?php echo e(($ra_search == '') ? 'selected' : ''); ?> value="">Select status</option>
                                        <option <?php echo e(($ra_search == '0') ? 'selected' : ''); ?> value="10">Pending</option>
                                        <option <?php echo e(($ra_search == '1') ? 'selected' : ''); ?> value="1">Approved</option>
                                        <option <?php echo e(($ra_search == '2') ? 'selected' : ''); ?> value="2">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 mt-2">
                                <div class="form-group mb-2">
                                    <label>Assigned Shop</label>
                                    <select id="shop_search" name="shop_search" class="form-control" >
                                        <option value="">Select Shop</option>
                                        <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(($shop_search == $shop->id) ? 'selected' : ''); ?> value="<?php echo e($shop->id); ?>"><?php echo e($shop->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 mt-2">
                                <div class="form-group mb-2">
                                    <label>Delivery Approval Status</label>
                                    <select id="da_search" name="da_search" class="form-control" >
                                        <option <?php echo e(($da_search == '') ? 'selected' : ''); ?> value="">Select status</option>
                                        <option <?php echo e(($da_search == '0') ? 'selected' : ''); ?> value="10">Pending</option>
                                        <option <?php echo e(($da_search == '1') ? 'selected' : ''); ?> value="1">Approved</option>
                                        <option <?php echo e(($da_search == '2') ? 'selected' : ''); ?> value="2">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3 mt-2">
                                <div class="form-group mb-2">
                                    <label>Refund Type</label>
                                    <select id="refund_search" name="refund_search" class="form-control" >
                                        <option <?php echo e(($refund_search == '') ? 'selected' : ''); ?> value="">Select type</option>
                                        <option <?php echo e(($refund_search == 'wallet') ? 'selected' : ''); ?> value="wallet">Wallet</option>
                                        <option <?php echo e(($refund_search == 'cash') ? 'selected' : ''); ?> value="cash">Cash</option>
                                    </select>
                                </div>
                            </div>

                        <?php endif; ?>

                        <div class="col-auto mt-4">
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-warning">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            <hr>

            <table class="table aiz-table mb-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Code</th>
                        <?php if(Auth::user()->shop_id != NULL && Auth::user()->user_type == 'staff'): ?>
                            
                        <?php else: ?>
                            <th class="w-10">Order Shop</th>
                        <?php endif; ?>
                        <th data-breakpoints="xl">Request Date</th>
                        <th data-breakpoints="xl">Customer</th>
                        <th data-breakpoints="xl">Product</th>
                        <th data-breakpoints="xl">Reason</th>
                        <th data-breakpoints="xl">Price</th>
                        <th data-breakpoints="xl">Quantity</th>
                        <th data-breakpoints="xl">Refund Amount</th>

                        <?php if(Auth::user()->shop_id != NULL && Auth::user()->user_type == 'staff'): ?>
                            <th class="text-center">Delivery Boy</th>
                            <th class="text-center">Delivery Date</th>
                        <?php else: ?>
                            <th class="text-center">Request Approval</th>
                            <th class="text-center">Assigned Shop</th>
                        <?php endif; ?>

                        <th class="text-center">Delivery Approval</th>
                        <?php if(Auth::user()->shop_id != NULL && Auth::user()->user_type == 'staff'): ?>

                        <?php else: ?>
                            <th class="text-center">Refund Type</th>
                        <?php endif; ?>
                        <th class="text-center">Order Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <tr>
                            <td>
                                <?php echo e(($key+1) + ($orders->currentPage() - 1)*$orders->perPage()); ?>

                            </td>
                            
                            <td>
                                <?php echo e($order->order->code ?? ''); ?>

                            </td>
                            <?php if(Auth::user()->shop_id != NULL && Auth::user()->user_type == 'staff'): ?>
                            
                            <?php else: ?>
                                <td>
                                    <?php echo e($order->order->shop->name ?? ''); ?>

                                </td>
                            <?php endif; ?>

                            <td>
                                <?php echo e(($order->request_date) ? date('d-m-Y h:i A', strtotime($order->request_date)) : ''); ?>

                            </td>

                            <td>
                                <strong class="text-main"><?php echo e(json_decode($order->order->shipping_address)->name); ?></strong><br>
                                <?php echo e(json_decode($order->order->shipping_address)->email); ?><br>
                                <?php echo e(json_decode($order->order->shipping_address)->phone); ?><br>
                                <?php echo e(json_decode($order->order->shipping_address)->address); ?>,
                                <?php echo e(json_decode($order->order->shipping_address)->city); ?>

                                <?php echo e(json_decode($order->order->shipping_address)->state); ?>

                                <br>
                                <?php echo e(json_decode($order->order->shipping_address)->country); ?>

                            </td>
                            <td>
                                <?php echo e($order->product->name); ?>

                            </td>
                            <td>
                                <?php echo e($order->reason); ?>

                            </td>
                            <td>
                                <?php echo e($order->offer_price); ?>

                            </td>
                            <td>
                                <?php echo e($order->quantity); ?>

                            </td>
                            
                            <td>
                                <?php echo e($order->refund_amount); ?>

                            </td>

                            <?php if(Auth::user()->shop_id != NULL && Auth::user()->user_type == 'staff'): ?>
                                <td class="text-center">
                                    <?php if($order->delivery_status == 0): ?>
                                        <a href="<?php echo e(route('return-delivery', encrypt($order->id))); ?>" class="btn btn-sm btn-success">Find Nearest Agent</a><br>
                                        <?php if($order->delivery_boy != NULL): ?>
                                            <b class=""><?php echo e($order->deliveryBoy->name ?? ''); ?></b>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <b><?php echo e($order->deliveryBoy->name ?? ''); ?></b>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php echo e(($order->delivery_completed_date != NULL) ? date('d-m-Y H:i a',strtotime($order->delivery_completed_date)) : ''); ?>

                                </td>
                            <?php else: ?>
                                <td class="text-center">
                                    <?php if($order->admin_approval == 0): ?>
                                        <button class="btn btn-sm btn-success d-innline-block adminApprove" data-id="<?php echo e($order->id); ?>" data-status="1" data-type="admin"><?php echo e(translate('Approve')); ?></button>
                                        <button class="btn btn-sm btn-warning d-innline-block adminApprove" data-id="<?php echo e($order->id); ?>" data-status="2" data-type="admin"><?php echo e(translate('Reject')); ?></button>
                                    <?php else: ?>
                                        <?php if($order->admin_approval == 1): ?>
                                            <span class=" badge-soft-success">Approved</span>
                                        <?php elseif($order->admin_approval == 2): ?>
                                            <span class=" badge-soft-danger">Rejected</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($order->admin_approval == 1): ?>
                                        <?php if($order->delivery_status != 1): ?>
                                            <?php
                                                if($order->shop_id != null){
                                                    $color = 'border:2px solid #09c309';
                                                }else {
                                                    $color = 'border:2px solid red';
                                                }
                                            ?>
                                            <select id="shop_id<?php echo e($key); ?>" name="shop_id<?php echo e($key); ?>" class="form-control selectShop" data-refund="<?php echo e($order->id); ?>" style="<?php echo e($color); ?>">
                                                <option value="">Select Shop</option>
                                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php if($shop->id == old('shop_id',$order->shop_id)): ?> <?php echo e('selected'); ?> <?php endif; ?> value="<?php echo e($shop->id); ?>"><?php echo e($shop->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        <?php else: ?>
                                            <b><?php echo e($order->shop? $order->shop->name : 'N/A'); ?><b>
                                        <?php endif; ?>
                                       
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                            
                            <?php if(Auth::user()->shop_id != NULL && Auth::user()->user_type == 'staff'): ?>
                                <td class="text-center">
                                    <?php if($order->delivery_approval == 0 && $order->delivery_status == 1): ?>
                                        <button class="btn btn-sm btn-success d-innline-block deliveryApprove" data-id="<?php echo e($order->id); ?>" data-status="1" data-type="delivery"><?php echo e(translate('Approve')); ?></button>
                                        <button class="btn btn-sm btn-warning d-innline-block deliveryApprove" data-id="<?php echo e($order->id); ?>" data-status="2" data-type="delivery"><?php echo e(translate('Reject')); ?></button>
                                    <?php else: ?>
                                        <?php if($order->delivery_approval == 1): ?>
                                            <span class=" badge-soft-success">Approved</span>
                                        <?php elseif($order->delivery_approval == 2): ?>
                                            <span class=" badge-soft-danger">Rejected</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            <?php else: ?>
                                <td class="table-action text-center">
                                    <?php if($order->delivery_approval == 1): ?>
                                    <span class=" badge-soft-success">Approved</span>
                                    <?php elseif($order->delivery_approval == 2): ?>
                                        <span class=" badge-soft-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($order->delivery_approval == 1 && $order->refund_type == NULL): ?>
                                        <button class="btn btn-sm btn-success d-innline-block adminPaymentType" data-id="<?php echo e($order->id); ?>" data-type="wallet"><?php echo e(translate('Wallet')); ?></button>
                                        <button class="btn btn-sm btn-warning d-innline-block adminPaymentType" data-id="<?php echo e($order->id); ?>" data-type="cash"><?php echo e(translate('Cash')); ?></button>
                                    <?php elseif($order->refund_type != NULL): ?>
                                        <?php echo e(ucfirst($order->refund_type)); ?>

                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            
                    
                            <td class="text-center">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('return_orders.show', encrypt($order->id))); ?>" title="View">
                                    <i class="las la-eye"></i>
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
    
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $(document).on("click", ".adminApprove", function(e) {
            var status = $(this).attr('data-status');
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            var msg = (status == '1') ? "Do you want to approve this request?" : "Do you want to reject this request?";
            e.preventDefault();
            if (confirm(msg)) {
                $.ajax({
                    url: "<?php echo e(route('return-request-status')); ?>",
                    type: "POST",
                    data: {
                        id: id,
                        status:status,
                        type:type,
                        _token: '<?php echo e(@csrf_token()); ?>',
                    },
                    dataType: "html",
                    success: function() {
                        window.location.reload();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert("Error deleting! Please try again");
                    }
                });
            }
        });

        $(document).on("click", ".deliveryApprove", function(e) {
            var status = $(this).attr('data-status');
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            var msg = (status == '1') ? "Do you want to approve this request?" : "Do you want to reject this request?";
            e.preventDefault();
            if (confirm(msg)) {
                $.ajax({
                    url: "<?php echo e(route('return-request-status')); ?>",
                    type: "POST",
                    data: {
                        id: id,
                        status:status,
                        type:type,
                        _token: '<?php echo e(@csrf_token()); ?>',
                    },
                    dataType: "html",
                    success: function() {
                        window.location.reload();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert("Error deleting! Please try again");
                    }
                });
            }
        });

        $(document).on('change','.selectShop',function(){
            
            var shop_id = $(this).val();
            var refund_id = $(this).attr('data-refund');
            
            swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "<?php echo e(route('assign-shop-refund')); ?>",
                        type: "POST",
                        data: {
                            refund_id: refund_id,
                            shop_id : $(this).val(),
                            _token: '<?php echo e(@csrf_token()); ?>',
                        },
                        dataType: "html",
                        success: function(response) {
                            swal("Successfully updated!", {
                                    icon: "success",
                                });
                            
                            window.location.reload();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            swal("Something went wrong!", {
                                icon: "warning",
                            });
                        }
                    });
                }else{
                    $(this).val('');
                }
            });
        });

        $(document).on("click", ".adminPaymentType", function(e) {
            var type = $(this).attr('data-type');
            var id = $(this).attr('data-id');
            
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "<?php echo e(route('return-payment-type')); ?>",
                        type: "POST",
                        data: {
                            id: id,
                            type:type,
                            _token: '<?php echo e(@csrf_token()); ?>',
                        },
                        dataType: "html",
                        success: function() {
                            swal("Successfully updated!", {
                                    icon: "success",
                                });
                            
                            window.location.reload();
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert("Error deleting! Please try again");
                        }
                    });
                }
            });
         
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

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/sales/return_requests.blade.php ENDPATH**/ ?>