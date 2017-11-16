<a href="{{ route('admin::campaigns.destroy-receiver', [$campaign, $receiver]) }}"
   class="btn btn-danger btn-xs confirm-delete"
   data-id="{{ $receiver->id }}">
    <i class="fa fa-trash"></i> Delete
</a>
