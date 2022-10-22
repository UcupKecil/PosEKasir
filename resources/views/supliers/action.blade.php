<a href="{{ route('supliers.edit',$id) }}" data-toggle="tooltip" data-original-title="Edit" class="edit btn btn-success edit">
    Edit
    </a>
<a href="javascript:void(0)" onclick="deleteData({{ $id }})" data-id="{{ $id }}" data-toggle="tooltip" data-original-title="Delete" class="delete btn btn-danger">
    Delete
</a>
