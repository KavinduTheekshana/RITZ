<form action="{{ route('client.logout') }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>