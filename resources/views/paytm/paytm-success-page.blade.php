
<style>
    table, th, td {
      border: 1px solid black;
    }
    </style>

<div class="alert alert-success alert-dismissible fade" role="alert">
    <strong>Payment Has been Successfully Received</strong><br>
    <table or>
        <tr>
            <th>Order Id</th>
            <th>Transaction Id</th>
            <th>UserId</th>
            <th>Name</th>
            <th>Email</th>
           
        </tr>
        <tr>
            <td>{{ $data['order_id']; }}</td>
            <td>{{ $data['transaction_id']; }}</td>
            <td>{{ $data['user_id']; }}</td>
            <td>{{ $data['name']; }}</td>
            <td>{{ $data['email']; }}</td>
            
            
        </tr>
    </table>
</div>
<a href="{{route('paytm.purchase')}}">Check the demo again</a>