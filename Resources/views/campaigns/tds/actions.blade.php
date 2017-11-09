<a href="{{ route('admin::campaigns.destroy-user', [$campaign, $user->id]) }}"
   class="btn btn-danger btn-xs confirm-delete"
   data-id="{{ $user->id }}">
    <i class="fa fa-trash"></i> Delete
</a>
