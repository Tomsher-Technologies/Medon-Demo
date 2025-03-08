<?php $__env->startSection('content'); ?>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">All Categories</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-primary">
                    <span>Add new category</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-block d-md-flex">
            <h5 class="mb-0 h6 mr-4">Categories</h5>
            <form class="" id="sort_categories" action="" method="GET" style="width: 100%">

                <div class="row gutters-5">
                    <div class="col-md-4">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" data-live-search="true"
                            name="catgeory" id="" data-selected=<?php echo e($catgeory); ?>>
                            <option value="0">All</option>
                            <?php $__currentLoopData = getAllCategories()->where('parent_id', 0); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                <?php if($item->child): ?>
                                    <?php $__currentLoopData = $item->child; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo $__env->make('backend.product.categories.menu_child_category', [
                                            'category' => $cat,
                                            'selected_id' => 0,
                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="search"
                            name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?>
                            placeholder="Type name & Enter">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-warning w-100" type="submit">Filter</button>
                    </div>
                </div>

            </form>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th >#</th>
                        <th>Name</th>
                        <th >Parent Category</th>
                        
                        <th class="text-center">Order Level</th>
                        
                        
                        <th >Icon</th>
                        <th class="text-center">Status</th>
                        <th width="10%" class="text-right">Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key + 1 + ($categories->currentPage() - 1) * $categories->perPage()); ?></td>
                            <td><?php echo e($category->name); ?></td>
                            <td>
                                <?php
                                    $parent = \App\Models\Category::where('id', $category->parent_id)->first();
                                ?>
                                <?php if($parent != null): ?>
                                    <?php echo e($parent->name); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                           
                            <td class="text-center"><?php echo e($category->order_level); ?></td>
                            
                           
                            <td>
                                <?php if($category->icon != null): ?>
                                    <span class="avatar avatar-square avatar-xs">
                                        <img src="<?php echo e(uploaded_asset($category->icon)); ?>" alt="icon">
                                    </span>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="update_status(this)" value="<?php echo e($category->id); ?>"
                                        <?php if ($category->is_active == 1) {
                                            echo 'checked';
                                        } ?>>
                                    <span></span>
                                </label>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="<?php echo e(route('categories.edit', ['id' => $category->id, 'lang' => env('DEFAULT_LANGUAGE')])); ?>"
                                    title="Edit">
                                    <i class="las la-edit"></i>
                                </a>
                                
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="aiz-pagination">
                <?php echo e($categories->appends(request()->input())->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('modal'); ?>
    <?php echo $__env->make('modals.delete_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
    <script>
        function copy(that) {
            var inp = document.createElement('input');
            document.body.appendChild(inp)
            inp.value = that.textContent
            inp.select();
            document.execCommand('copy', false);
            inp.remove();
        }
    </script>
    <script type="text/javascript">
        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('<?php echo e(route('categories.featured')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Featured categories updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('<?php echo e(route('categories.status')); ?>', {
                _token: '<?php echo e(csrf_token()); ?>',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Category status updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/product/categories/index.blade.php ENDPATH**/ ?>