<?php setlocale(LC_ALL, 'es'); ?>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>OC Interna: Abastecimiento</title>
  <meta name="description" content="">
  <meta name="author" content="Servicio de Salud Iquique">
  <style media="screen">
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 0.75rem;
    }

    .content {
      margin: 0 auto;
      /*border: 1px solid #F2F2F2;*/
      width: 724px;
      /*height: 1100px;*/
    }

    .monospace {
      font-family: "Lucida Console", Monaco, monospace;
    }

    .pie_pagina {
      margin: 0 auto;
      /*border: 1px solid #F2F2F2;*/
      width: 724px;
      height: 26px;
      position: fixed;
      bottom: 0;
    }

    .seis {
      font-size: 0.6rem;
    }

    .siete {
      font-size: 0.7rem;
    }

    .ocho {
      font-size: 0.8rem;
    }

    .nueve {
      font-size: 0.9rem;
    }

    .plomo {
      background-color: F3F1F0;
    }

    .titulo {
      text-align: center;
      font-size: 1.2rem;
      font-weight: bold;
      padding: 4px 0 6px;
    }

    .center {
      text-align: center;
    }

    .left {
      text-align: left;
    }

    .right {
      text-align: right;
    }

    .justify {
      text-align: justify;
    }

    .indent {
      text-indent: 30px;
    }

    .uppercase {
      text-transform: uppercase;
    }

    #firmas {
      margin-top: 80px;
    }

    #firmas>div {
      display: inline-block;
    }

    .li_letras {
      list-style-type: lower-alpha;
    }

    table {
      border: 1px solid grey;
      border-collapse: collapse;
      padding: 0 4px 0 4px;
      width: 100%;
    }

    th,
    td {
      border: 1px solid grey;
      border-collapse: collapse;
      padding: 0 4px 0 4px;
    }

    .column {
      float: left;
      width: 50%;
    }

    /* Clear floats after the columns */
    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    @media all {
      .page-break {
        display: none;
      }
    }

    @media print {
      .page-break {
        display: block;
        page-break-before: always;
      }
    }
  </style>
</head>

<body>
  <div class="content">

    <div class="content">
      <img style="padding-bottom: 4px;" src="images/logo_pluma.jpg" width="120" alt="Logo Servicio de Salud"><br>


      <div class="siete" style="padding-top: 3px;">
        <strong>{{ env('APP_SS') }}</strong>
      </div>
      <div class="seis" style="padding-top: 4px;">
        <strong>{{ env('APP_SS_ADDRESS') }}</strong>
      </div>
      <div class="seis" style="padding-top: 4px;">
        <strong>{{ env('APP_SS_RUN') }}</strong>
      </div>

      <div class="right" style="float: right;">
        <div class="left" style="padding-bottom: 6px;">
          <strong>FECHA DE ORDEN: {{ $internalPurchaseOrder->created_at->format('d-m-Y') }}</strong>
        </div>
      </div>

      <div style="clear: both; padding-bottom: 5px">&nbsp;</div>

      <div class="center">
        <div class="titulo" style="padding-bottom: 6px;">
          <strong>ORDEN DE COMPRA Nº {{ $internalPurchaseOrder->id }} / {{ $internalPurchaseOrder->created_at->format('Y') }}</strong>
        </div>
      </div>

      <div style="clear: both; padding-bottom: 5px">&nbsp;</div>

      <table>
          <tr>
              <th align="left" width="10 px">PROVEEDOR</th>
              <td>{{ $internalPurchaseOrder->supplier->name }}</td>
              <th align="left" width="10 px">RUN</th>
              <td>{{ $internalPurchaseOrder->supplier->run }}-{{ $internalPurchaseOrder->supplier->dv }}</td>
              <th align="left" width="10 px">EMISOR</th>
              <td>{{ $internalPurchaseOrder->user->FullName }}</td>
          </tr>
          <tr>
              <th align="left">DIRECCION</th>
              <td></td>
              <th align="left">CIUDAD</th>
              <td></td>
              <th align="left">REQ. FOLIO</th>
              <td>{{ $internalPurchaseOrder->purchasingProcess->requestForm->id }}</td>
          </tr>
          <tr>
              <th align="left">FONO</th>
              <td></td>
              <th align="left">COND. PAGO</th>
              <td>{{ $internalPurchaseOrder->payment_condition }}</td>
              <th align="left"></th>
              <td></td>
          </tr>
      </table>

      <div style="clear: both; padding-bottom: 5px">&nbsp;</div>

      <p>Solicito a Ud.(s) remitir al Servicio Salud Iquique los siguientes articulos y/o  servicios:</p>

      <div style="clear: both; padding-bottom: 5px">&nbsp;</div>

      <table>
        <thead>
          <tr>
              <th width="">ITEM</th>
              <th width="">NOMBRE DEL ARTICULO</th>
              <th width="">UND</th>
              <th width="">CANTIDAD</th>
              <th width="">PRECIO UNITARIO</th>
              <th width="">TOTAL</th>
          </tr>
        </thead>
        <tbody>
          @foreach($internalPurchaseOrder->purchasingProcess->details as $key => $detail)
          <tr>
              <td width="">{{ $key+1 }}</td>
              <td>{{ $detail->article }}</td>
              <td></td>
              <td align="right">{{ $detail->pivot->quantity }}</td>
              <td align="right">${{ number_format($detail->pivot->unit_value,0,",",".") }}</td>
              <td align="right">${{ number_format($detail->pivot->expense,0,",",".") }}</td>
          </tr>
          @endforeach
          <tr>
              <td colspan="4"></td>
              <th align="right">TOTAL NETO</th>
              <td></td>
          </tr>
          <tr>
              <td colspan="4"></td>
              <th align="right">IVA</th>
              <td></td>
          </tr>
          <tr>
              <td colspan="4"></td>
              <th align="right">TOTAL</th>
              <td></td>
          </tr>
        </tbody>
      </table>

      <p><strong>OBSERVACIONES</strong></p>
      <p>DATOS FACTURACIÓN:</p>
      <p>A Nombre del: Servicio de Salud Iquique.</p>
      <p>Rut: 61.606.100-3</p>

    </div>
</body>

</html>
