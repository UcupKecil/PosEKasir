<!DOCTYPE html>
<html>
<head>
   <title>Nota PDF</title>
   <style type="text/css">
      body{
         font-size:12px;
      }
      h3{
         font-size:18px;
      }
      table td{font: arial 2px;}
      table.data td,
      table.data th{
         /* border: 1px solid #ccc;
         padding: 2px; */
      }
      table.data th{
         text-align: center;
      }
      table.data{ border-collapse: collapse }
   </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
    integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>

<div style="text-align:center; width:100%">
   <h3>{{ config('app.name') }}</h3>
   @foreach($pembelian as $data)
         {{ tanggal_indonesia(substr($data->created_at, 0, 10), false) }}
    @endforeach

   <br>


   <table>

      <tr>

         <td align="left">Kasir</td>
         <td align="right">Noval</td>
      </tr>
   </table>
</div>
<div width="100%">
   <table>
    <thead>
        <tr>
            <th scope="col">Produk</th>
            <th scope="col">Pcs</th>
            <th scope="col">Total</th>

        </tr>
    </thead>
   @foreach($detail as $data)
      <tr>
         <td>{{ $data->name }}</td>
         <td>{{ $data->pcs }}</td>
         <td>{{ format_uang($data->harga) }}</td>
      </tr>
      @endforeach
      <tr><td>&nbsp;</td></tr>
      <tr>
         <td  colspan="3" align="left">Total Harga</td>
         @foreach($totals as $total)
         <td align="left">{{ format_uang($total->totalharga) }}</td>
         @endforeach


      </tr>
      <tr>
         <td  colspan="3" align="left">Dibayar</td>
         @foreach($pengeluarans as $total)
         <td align="left">{{ format_uang($total->totalbayar) }}</td>
         @endforeach
      </tr>
      <tr>
         <td  colspan="3" align="left">Keterangan</td>
         {{-- <td align="right">{{ $pembelian->suplier_id }}</td> --}}
      </tr>
   </table>

</div>



<div style="text-align:center">
   <b>Terimakasih telah berbelanja dan sampai jumpa</b>
</div>

</body>
</html>
