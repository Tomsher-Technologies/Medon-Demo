<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="h6">User Search Report</h1>
                </div>
                <div class="card-body">

                    <form action="<?php echo e(route('user_search_report.index')); ?>" method="GET">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <div class="form-group mb-0">
                                    <input type="text" class="aiz-date-range form-control" value="<?php echo e($date); ?>" name="date" placeholder="Filter by date" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true" autocomplete="off">
                                </div>
                            </div>
                        
                            <div class="col-auto">
                                <div class="form-group mb-0">
                                    <button type="submit" class="btn btn-warning">Filter</button>
                                    <a class="btn btn-info" href="<?php echo e(route('user_search_report.index')); ?>" >Reset</a>
                                    <a href="<?php echo e(route('export.search_report')); ?>"  class="btn btn-danger" style="border-radius: 30px;">Excel Export</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Search Key</th>
                                <th>User</th>
                                <th>IP Address</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $searches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $searche): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key + 1 + ($searches->currentPage() - 1) * $searches->perPage()); ?></td>
                                    <td><?php echo e($searche->query); ?></td>
                                    <td>
                                        <?php if($searche->user_id): ?>
                                            <a
                                                href="<?php echo e(route('user_search_report.index', ['user_id' => $searche->user_id])); ?>">
                                                <?php echo e($searche->user->name); ?>

                                            </a>
                                        <?php else: ?>
                                            GUEST
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($searche->ip_address); ?></td>
                                    <td><?php echo e($searche->created_at->format('d-m-Y h:i A')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="aiz-pagination mt-4">
                        <?php echo e($searches->appends(request()->input())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/reports/user_search_report.blade.php ENDPATH**/ ?>