@extends('backend.layouts.app')

@section('style')
<style>
    #loadingEffect {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1051;
        background: rgba(255, 255, 255, 0.8);
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .char-count {
        font-size: 0.9em;
        color: gray;
    }
    .warning {
        color: red;
    }
</style>
@endsection
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">All Notifications</h1>
                
            </div>
            
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">Notifications</h5>
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#notifyModal" >Create New Notification >></a>
        </div>
        <div class="card-body">
            <table class="table aiz-table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th class="text-center">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($notifications[0]))
                        @foreach ($notifications as $key => $not)
                            <tr>
                                <td class="text-center">{{ ($key+1) + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                                <td>{{ $not->title }}</td>
                                <td >{{ $not->message }}</td>
                                <td class="text-center">{{ date('d M, Y H:i A', strtotime($not->created_at)) }}</td>
                                
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $notifications->appends(request()->input())->links('pagination::bootstrap-4') }}
            </div>
        </div>

       

        <div class="modal fade" id="notifyModal" tabindex="-1" aria-labelledby="commentModalLabel"
            aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">
                <form action="{{ route('send.notification') }}" method="post" id="commentForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create New Notification</h5>
                            <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" maxlength="50" required name="title" placeholder="Enter title">
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea class="form-control" id="message" maxlength="100" required name="message" rows="4" placeholder="Enter message"></textarea>
                                <div class="char-count" id="charCount">0 / 100 characters</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Send Notification</button>
                        </div>

                        {{-- <div id="loadingEffect" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                              <span class="sr-only">Sending...</span>
                            </div>
                        </div> --}}
                    </div>
                </form>
            </div>
        </div>

        <div class="modal spinnerId" id="" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="spinner-border " aria-hidden="true"></div> &nbsp;
                            <strong role="status"> Sending Notification...</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const textarea = document.getElementById('message');
        const charCountDiv = document.getElementById('charCount');
        const maxChars = 100;

        textarea.addEventListener('input', function () {
            const charCount = textarea.value.length;
            charCountDiv.textContent = `${charCount} / ${maxChars} characters`;

            if (charCount > maxChars) {
                charCountDiv.classList.add('warning');
            } else {
                charCountDiv.classList.remove('warning');
            }
        });



        $(document).ready(function() {
            $('#notifyModal').on('submit', function (e) {
                // Show the loading spinner
                // $('#loadingEffect').show();
                $('.spinnerId').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            });
        });
    
    </script>
@endsection
