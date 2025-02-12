<!DOCTYPE html>
<html>
<body>

<p>Click on the "Choose File" button to upload a file:</p>
@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div style="color: red;">{{ session('error') }}</div>
    <table border="1">
        <tr>
            <th>Row Data</th>
            <th>Errors</th>
        </tr>
        @foreach(session('failedRows') as $failedRow)
            <tr>
                <td>{{ implode(', ', $failedRow['row']) }}</td>
                <td>{{ implode('; ', $failedRow['errors']) }}</td>
            </tr>
        @endforeach
    </table>
@endif

<form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>

</body>
</html>
