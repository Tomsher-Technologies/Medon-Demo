<?php $__env->startSection('content'); ?>
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3"><?php echo e(translate('All uploaded files')); ?></h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="<?php echo e(route('uploaded-files.create')); ?>" class="btn btn-primary">
				<span><?php echo e(translate('Upload New File')); ?></span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <form id="sort_uploads" action="">
        <div class="card-header row gutters-5">
            <div class="col-md-3">
                <h5 class="mb-0 h6"><?php echo e(translate('All files')); ?></h5>
            </div>
            <div class="col-md-3 ml-auto mr-0">
                <select class="form-control form-control-xs aiz-selectpicker" name="sort" onchange="sort_uploads()">
                    <option value="newest" <?php if($sort_by == 'newest'): ?> selected="" <?php endif; ?>>Sort by newest</option>
                    <option value="oldest" <?php if($sort_by == 'oldest'): ?> selected="" <?php endif; ?>>Sort by oldest</option>
                    <option value="smallest" <?php if($sort_by == 'smallest'): ?> selected="" <?php endif; ?>>Sort by smallest</option>
                    <option value="largest" <?php if($sort_by == 'largest'): ?> selected="" <?php endif; ?>>Sort by largest</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-xs" name="search" placeholder="Search your files" value="<?php echo e($search); ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
    <div class="card-body">
		<div class="text-right mb-3">
			<a href="javascript:void(0)" class="btn btn-danger btn-sm bulkDelete"  data-target="#bulkdelete-modal">
				<i class="las la-trash"></i> Delete Selected
			</a>
		</div>
    	<div class="row gutters-5">
			
    		<?php $__currentLoopData = $all_uploads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    			<?php
    				if($file->file_original_name == null){
    				    $file_name = translate('Unknown');
    				}else{
    					$file_name = $file->file_original_name;
	    			}
    			?>
    			<div class="col-auto w-140px w-lg-220px">
    				<div class="aiz-file-box">
						
    					<div class="dropdown-file" >
    						<a class="dropdown-link" data-toggle="dropdown">
    							<i class="la la-ellipsis-v"></i>
    						</a>
    						<div class="dropdown-menu dropdown-menu-right">
    							<a href="javascript:void(0)" class="dropdown-item" onclick="detailsInfo(this)" data-id="<?php echo e($file->id); ?>">
    								<i class="las la-info-circle mr-2"></i>
    								<span>Details Info</span>
    							</a>
    							<a href="<?php echo e(storage_asset($file->file_name)); ?>" target="_blank" download="<?php echo e($file_name); ?>.<?php echo e($file->extension); ?>" class="dropdown-item">
    								<i class="la la-download mr-2"></i>
    								<span>Download</span>
    							</a>
    							<a href="javascript:void(0)" class="dropdown-item" onclick="copyUrl(this)" data-url="<?php echo e(storage_asset($file->file_name)); ?>">
    								<i class="las la-clipboard mr-2"></i>
    								<span>Copy Link</span>
    							</a>
    							<a href="javascript:void(0)" class="dropdown-item confirm-alert" data-href="<?php echo e(route('uploaded-files.destroy', $file->id )); ?>" data-target="#delete-modal">
    								<i class="las la-trash mr-2"></i>
    								<span>Delete</span>
    							</a>
    						</div>
    					</div>
    					<div class="card card-file aiz-uploader-select c-default" title="<?php echo e($file_name); ?>.<?php echo e($file->extension); ?>">
    						<div class="card-file-thumb">
    							<?php if($file->type == 'image'): ?>
    								<img src="<?php echo e(storage_asset($file->file_name)); ?>" asd class="img-fit">
    							<?php elseif($file->type == 'video'): ?>
    								<i class="las la-file-video"></i>
    							<?php else: ?>
    								<i class="las la-file"></i>
    							<?php endif; ?>
    						</div>
    						<div class="card-body">
    							<h6 class="d-flex">
    								<span class="text-truncate title"><?php echo e($file_name); ?></span>
    								<span class="ext">.<?php echo e($file->extension); ?></span>
    							</h6>
    							<p>
									<span><?php echo e(formatBytes($file->file_size)); ?></span>
									<span style="float: right;"><input type="checkbox" class="multi-delete-checkbox" value="<?php echo e($file->id); ?>"></span>
								</p>
								
    						</div>
    					</div>
    				</div>
    			</div>
    		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    	</div>
		<div class="aiz-pagination mt-3">
			<?php echo e($all_uploads->appends(request()->input())->links()); ?>

		</div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('modal'); ?>
<div id="delete-modal" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">Delete Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1">Are you sure to delete this file?</p>
                <button type="button" class="btn btn-link mt-2" data-dismiss="modal">Cancel</button>
                <a href="" class="btn btn-primary mt-2 comfirm-link" autofocus>Delete</a>
            </div>
        </div>
    </div>
</div>

<div id="bulkdelete-modal" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">Delete Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mt-1">Are you sure to delete?</p>
                <button type="button" class="btn btn-link mt-2" data-dismiss="modal">Cancel</button>
                <a href="" class="btn btn-primary mt-2 " id="delete-selected-file" autofocus>Delete</a>
            </div>
        </div>
    </div>
</div>

<div id="info-modal" class="modal fade">
	<div class="modal-dialog modal-dialog-right">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title h6">File Info</h5>
				<button type="button" class="close" data-dismiss="modal">
				</button>
			</div>
			<div class="modal-body c-scrollbar-light position-relative" id="info-modal-content">
				<div class="c-preloader text-center absolute-center">
                    <i class="las la-spinner la-spin la-3x opacity-70"></i>
                </div>
			</div>
		</div>
	</div>
</div>
<style>
	.multi-delete-checkbox{
		width: 18px;
		height: 18px;
		cursor: pointer;
	}
</style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
	<script type="text/javascript">
		function detailsInfo(e){
            $('#info-modal-content').html('<div class="c-preloader text-center absolute-center"><i class="las la-spinner la-spin la-3x opacity-70"></i></div>');
			var id = $(e).data('id')
			$('#info-modal').modal('show');
			$.post('<?php echo e(route('uploaded-files.info')); ?>', {_token: AIZ.data.csrf, id:id}, function(data){
                $('#info-modal-content').html(data);
				// console.log(data);
			});
		}
		function copyUrl(e) {
			var url = $(e).data('url');
			var $temp = $("<input>");
		    $("body").append($temp);
		    $temp.val(url).select();
		    try {
			    document.execCommand("copy");
			    AIZ.plugins.notify('success', 'Link copied to clipboard');
			} catch (err) {
			    AIZ.plugins.notify('danger', 'Oops, unable to copy');
			}
		    $temp.remove();
		}
        function sort_uploads(el){
            $('#sort_uploads').submit();
        }

		
		$(".bulkDelete").click(function (e) {
			e.preventDefault();
			var target = $(this).data("target");
			$(target).modal("show");
		});

		$('#delete-selected-file').on('click', function(e) {

			e.preventDefault();
			$('#bulkdelete-modal').modal("hide");

			var selectedIds = [];
			$('.multi-delete-checkbox:checked').each(function() {
				selectedIds.push($(this).val());
			});

			if(selectedIds.length == 0){
				AIZ.plugins.notify('danger', 'Please select at least one file to delete.');
				return;
			}else{
				$.ajax({
					url: "<?php echo e(route('uploaded-files.multi-delete')); ?>",
					type: 'POST',
					data: {
						_token: "<?php echo e(csrf_token()); ?>",
						ids: selectedIds
					},
					success: function(response) {
						if(response.success){
							AIZ.plugins.notify('success', 'Files deleted successfully.');
							setTimeout(function() {
								window.location.reload();
							}, 2000);

						} else {
							AIZ.plugins.notify('danger', 'Failed to delete files.');
						}
					}
				});
			}
		});

	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\jisha\Medon\resources\views/backend/uploaded_files/index.blade.php ENDPATH**/ ?>