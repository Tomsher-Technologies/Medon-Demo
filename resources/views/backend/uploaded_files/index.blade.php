@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All uploaded files')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="{{ route('uploaded-files.create') }}" class="btn btn-primary">
				<span>{{translate('Upload New File')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <form id="sort_uploads" action="">
        <div class="card-header row gutters-5">
            <div class="col-md-3">
                <h5 class="mb-0 h6">{{translate('All files')}}</h5>
            </div>
            <div class="col-md-3 ml-auto mr-0">
                <select class="form-control form-control-xs aiz-selectpicker" name="sort" onchange="sort_uploads()">
                    <option value="newest" @if($sort_by == 'newest') selected="" @endif>Sort by newest</option>
                    <option value="oldest" @if($sort_by == 'oldest') selected="" @endif>Sort by oldest</option>
                    <option value="smallest" @if($sort_by == 'smallest') selected="" @endif>Sort by smallest</option>
                    <option value="largest" @if($sort_by == 'largest') selected="" @endif>Sort by largest</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control form-control-xs" name="search" placeholder="Search your files" value="{{ $search }}">
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
    		@foreach($all_uploads as $key => $file)
    			@php
    				if($file->file_original_name == null){
    				    $file_name = translate('Unknown');
    				}else{
    					$file_name = $file->file_original_name;
	    			}
    			@endphp
    			<div class="col-auto w-140px w-lg-220px">
    				<div class="aiz-file-box">
    					<div class="dropdown-file" >
    						<a class="dropdown-link" data-toggle="dropdown">
    							<i class="la la-ellipsis-v"></i>
    						</a>
    						<div class="dropdown-menu dropdown-menu-right">
    							<a href="javascript:void(0)" class="dropdown-item" onclick="detailsInfo(this)" data-id="{{ $file->id }}">
    								<i class="las la-info-circle mr-2"></i>
    								<span>Details Info</span>
    							</a>
    							<a href="{{ storage_asset($file->file_name) }}" target="_blank" download="{{ $file_name }}.{{ $file->extension }}" class="dropdown-item">
    								<i class="la la-download mr-2"></i>
    								<span>Download</span>
    							</a>
    							<a href="javascript:void(0)" class="dropdown-item" onclick="copyUrl(this)" data-url="{{ storage_asset($file->file_name) }}">
    								<i class="las la-clipboard mr-2"></i>
    								<span>Copy Link</span>
    							</a>
    							<a href="javascript:void(0)" class="dropdown-item confirm-alert" data-href="{{ route('uploaded-files.destroy', $file->id ) }}" data-target="#delete-modal">
    								<i class="las la-trash mr-2"></i>
    								<span>Delete</span>
    							</a>
    						</div>
    					</div>
    					<div class="card card-file aiz-uploader-select c-default" title="{{ $file_name }}.{{ $file->extension }}">
    						<div class="card-file-thumb">
    							@if($file->type == 'image')
    								<img src="{{ storage_asset($file->file_name) }}" asd class="img-fit">
    							@elseif($file->type == 'video')
    								<i class="las la-file-video"></i>
    							@else
    								<i class="las la-file"></i>
    							@endif
    						</div>
    						<div class="card-body">
    							<h6 class="d-flex">
    								<span class="text-truncate title">{{ $file_name }}</span>
    								<span class="ext">.{{ $file->extension }}</span>
    							</h6>
    							<p>
    							    <span>{{ formatBytes($file->file_size) }}</span>
									<span style="float: right;"><input type="checkbox" class="multi-delete-checkbox" value="{{ $file->id }}"></span>
    							</p>
    						</div>
    					</div>
    				</div>
    			</div>
    		@endforeach
    	</div>
		<div class="aiz-pagination mt-3">
			{{ $all_uploads->appends(request()->input())->links() }}
		</div>
    </div>
</div>
@endsection
@section('modal')
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
@endsection
@section('script')
	<script type="text/javascript">
		function detailsInfo(e){
            $('#info-modal-content').html('<div class="c-preloader text-center absolute-center"><i class="las la-spinner la-spin la-3x opacity-70"></i></div>');
			var id = $(e).data('id')
			$('#info-modal').modal('show');
			$.post('{{ route('uploaded-files.info') }}', {_token: AIZ.data.csrf, id:id}, function(data){
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
					url: "{{ route('uploaded-files.multi-delete') }}",
					type: 'POST',
					data: {
						_token: "{{ csrf_token() }}",
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
@endsection