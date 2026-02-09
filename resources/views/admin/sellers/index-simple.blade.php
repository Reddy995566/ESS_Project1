<!DOCTYPE html>
<html>
<head>
    <title>Sellers - Debug</title>
</head>
<body>
    <h1>Sellers Debug Page</h1>
    
    <p>Total Sellers: {{ $sellers->total() }}</p>
    <p>Current Page Count: {{ $sellers->count() }}</p>
    
    @if($sellers->count() > 0)
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Business Name</th>
                    <th>Owner</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Products</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sellers as $seller)
                <tr>
                    <td>{{ $seller->id }}</td>
                    <td>{{ $seller->business_name }}</td>
                    <td>{{ $seller->user->name ?? 'N/A' }}</td>
                    <td>{{ $seller->business_email }}</td>
                    <td>{{ $seller->status }}</td>
                    <td>{{ $seller->products_count ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No sellers found in database.</p>
    @endif
    
    <hr>
    <h2>Debug Info:</h2>
    <pre>
Sellers Object Type: {{ get_class($sellers) }}
Total: {{ $sellers->total() }}
Per Page: {{ $sellers->perPage() }}
Current Page: {{ $sellers->currentPage() }}
    </pre>
</body>
</html>
