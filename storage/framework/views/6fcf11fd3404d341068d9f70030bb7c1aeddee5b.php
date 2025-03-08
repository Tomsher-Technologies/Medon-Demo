<?php $__env->startSection('content'); ?>

<div class="card">
    
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">All Cancel Requests</h5>
            </div>
        </div>

        <div class="card-body">
            
                <form class="" action="" id="sort_orders" method="GET">
                    <div class="row">
                        
                        <div class="col-lg-4 mt-2">
                            <div class="form-group mb-2">
                                <label>Order Code</label>
                                <input type="text" class="form-control" id="search" name="search"<?php if(isset($search)): ?> value="<?php echo e($search); ?>" <?php endif; ?> placeholder="Type Order code & hit Enter">
                            </div>
                        </div>

                        <div class="col-lg-4 mt-2">
                            <div class="form-group mb-2">
                                <label>Request Approval Status</label>
                                <select id="ca_search" name="ca_search" class="form-control" >
                                    <option <?php echo e(($ca_search == '') ? 'selected' : ''); ?> value="">Select status</option>
                                    <option <?php echo e(($ca_search == '0') ? 'selected' : ''); ?> value="10">Pending</option>
                                    <option <?php echo e(($ca_search == '1') ? 'selected' : ''); ?> value="1">Approved</option>
                                    <option <?php echo e(($ca_search == '2') ? 'selected' : ''); ?> value="2">Rejected</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 mt-2">
                            <div class="form-group mb-2">
                                <label>Request Date</label>
                                <input type="text" class="aiz-date-range form-control" value="<?php echo e($date); ?>" name="date" placeholder="Filter by request date" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-lg-4 mt-2">
                            <div class="form-group mb-2">
                                <label>Refund Type</label>
                                <select id="refund_search" name="refund_search" class="form-control" >
                                    <option <?php echo e(($refund_search == '') ? 'selected' : ''); ?> value="">Select type</option>
                                    <option <?php echo e(($refund_search == 'wallet') ? 'selected' : ''); ?> value="wallet">Wallet</option>
                                    <option <?php echo e(($refund_search == 'cash') ? 'selected' : ''); ?> value="cash">Cash</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-auto mt-3">
                            <div class="form-group mb-0" style="margin: inherit;">
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
                        <th>Request Date</th>
                        <th data-breakpoints="xl">Customer</th>
                        <th class="w-25">Reason</th>
                        <th data-breakpoints="xl">Amount</th>
                        <th class="text-center">Request Approval</th>
                        <th class="text-center">Refund Type</th>
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
                                <?php echo e($order->code ?? ''); ?>

                            </td>

                            <td>
                                <?php echo e(($order->cancel_request_date) ? date('d-m-Y H:i a', strtotime($order->cancel_request_date)) : ''); ?>

                            </td>

                            <td>
                                <strong class="text-main"><?php echo e(json_decode($order->shipping_address)->name); ?></strong><br>
                                <?php echo e(json_decode($order->shipping_address)->email); ?><br>
                                <?php echo e(json_decode($order->shipping_address)->phone); ?><br>
                                <?php echo e(json_decode($order->shipping_address)->address); ?>,
                                <?php echo e(json_decode($order->shipping_address)->city); ?>

                                <?php echo e(json_decode($order->shipping_address)->state); ?>

                                <br>
                                <?php echo e(json_decode($order->shipping_address)->country); ?>

                            </td>
                           
                            <td>
                                <?php echo e($order->cancel_reason); ?>

                            </td>
                            <td>
                                <?php echo e($order->grand_total); ?>

                            </td>
                           
                            <td class="text-center">
                                <?php if($order->cancel_approval == 0): ?>
                                    <button class="btn btn-sm btn-success d-innline-block adminApprove" data-id="<?php echo e($order->id); ?>" data-status="1"><?php echo e(translate('Approve')); ?></button>
                                    <button class="btn btn-sm btn-warning d-innline-block adminApprove" data-id="<?php echo e($order->id); ?>" data-status="2"><?php echo e(translate('Reject')); ?></button>
                                <?php else: ?>
                                    <?php if($order->cancel_approval == 1): ?>
                                        <span class=" badge-soft-success">Approved</span>
                                    <?php elseif($order->cancel_approval == 2): ?>
                                        <span class=" badge-soft-danger">Rejected</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <?php if($order->cancel_approval == 1 && $order->cancel_refund_type == NULL): ?>
                                    <button class="btn btn-sm btn-success d-innline-block cancelPaymentType" data-id="<?php echo e($order->id); ?>" data-type="wallet"><?php echo e(translate('Wallet')); ?></button>
                                    <button class="btn btn-sm btn-warning d-innline-block cancelPaymentType" data-id="<?php echo e($order->id); ?>" data-type="cash"><?php echo e(translate('Cash')); ?></button>
                                <?php elseif($order->cancel_refund_type != NULL): ?>
                                    <?php echo e(ucfirst($order->cancel_refund_type)); ?>

                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="<?php echo e(route('cancel_orders.show', encrypt($order->id))); ?>" title="View">
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
            var msg = (status == '1') ? "Do you want to approve this request?" : "Do you want to reject this request?";
            
            swal({
                title: "Are you sure?",
                text: msg,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "<?php echo e(route('cancel-request-status')); ?>",
                        type: "POST",
                        data: {
                            id: id,
                            status:status,
                            _token: '<?php echo e(@csrf_token()); ?>',
                        },
                        dataType: "html",
                        success: function(response) {
                            if(response == 1){
                                swal("Successfully updated!", {
                                    icon: "success",
                                });
                            }else{
                                swal("Something went wrong!", {
                                    icon: "error",
                                });
                            }
                            setTimeout(function () { 
                                location.reload(true); 
                            }, 3000); 
                        }
                    });
                }else{
                    $(this).val('');
                }
            });
           
        });

       
        $(document).on("click", ".cancelPaymentType", function(e) {
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
                        url: "<?php echo e(route('cancel-payment-type')); ?>",
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
                            setTimeout(function () { 
                                window.location.reload(); 
                            }, 3000); 
                            
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            swal("Error! Please try again", {
                                icon: "error",
                            });
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/sales/cancel_requests.blade.php ENDPATH**/ ?>