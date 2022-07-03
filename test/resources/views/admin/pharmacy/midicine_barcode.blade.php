@extends('layouts.admin')
@section('css')
<link href="{{asset('public/assets/plugins/tabs/tabs.css')}}" rel="stylesheet" />
    <style>
        #parent {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            flex-flow: column wrap;
        }
        small{
            font-size: 8px;
        }
        td.text-center{
            padding: 5px;
        }
    </style>
@endsection
@section('directory')
    <li class="breadcrumb-item active" aria-current="page">Medicine Barcode</li>
@endsection
@section('title') Medicine Barcode @endsection

@section('content')
    <div class="card">

        <div class="card-body">
            <div class="panel panel-primary">
                <div class="tab_wrapper first_tab">
                    <ul class="tab_list">
                        <li class="active" rel="tab_1_1">BarCode</li>
                        <li rel="tab_1_2" class="">QrCode</li>
                    </ul>

            <div class="content_wrapper">
                <div  class="accordian_header tab_1_1 active">Tab 1<span class="arrow"></span></div><div class="tab_content first tab_1_1 active"  style="display: block;">
                        {{-- br --}}
                        <div id="printableArea">
                                <div class="bar">
                                    <table class="table table-bordered">
                                    <tbody>
                                        @for ($i=0 ;$i<10; $i++)
                                        <tr>
                                    
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:image/png;base64,{{$code}}"  /></div>
                                                <small><b>{{$text[0]->medicine_name}}</b></small>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:image/png;base64,{{$code}}"  /></div>
                                                <small><b>{{$text[0]->medicine_name}}</b></small>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:image/png;base64,{{$code}}"  /></div>
                                                <small><b>{{$text[0]->medicine_name}}</b></small>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:image/png;base64,{{$code}}"  /></div>
                                                <small><b>{{$text[0]->medicine_name}}</b></small>
                                            </td>
                                         
                                        <tr>
                                        @endfor


                                    </tbody>
                                    </table>
                                </div>
                            </div>
                            <a class="btn btn-info" href="#" onclick="printDiv('printableArea')">Print</a>
                        </div>

                        <div class="accordian_header tab_1_2 undefined">Tab 2<span class="arrow"></span></div><div class="tab_content tab_1_2"  style="display: none;">
                            <div id="printableArea2">
                                <div class="bar">
                                    <table class="table table-bordered">
                                    <tbody>
                                        @for ($i=0 ;$i<10; $i++)
                                        <tr>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:{{$qrCode->getContentType()}}';base64,{{$qrCode->generate()}}" /></div>
                                                <small>{{$text[0]->medicine_name}}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:{{$qrCode->getContentType()}}';base64,{{$qrCode->generate()}}" /></div>
                                                <small>{{$text[0]->medicine_name}}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:{{$qrCode->getContentType()}}';base64,{{$qrCode->generate()}}" /></div>
                                                <small>{{$text[0]->medicine_name}}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:{{$qrCode->getContentType()}}';base64,{{$qrCode->generate()}}" /></div>
                                                <small>{{$text[0]->medicine_name}}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:{{$qrCode->getContentType()}}';base64,{{$qrCode->generate()}}" /></div>
                                                <small>{{$text[0]->medicine_name}}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:{{$qrCode->getContentType()}}';base64,{{$qrCode->generate()}}" /></div>
                                                <small>{{$text[0]->medicine_name}}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="">
                                                    <small>PMS Medical Complex</small>
                                                </div>
                                                <div><img src="data:{{$qrCode->getContentType()}}';base64,{{$qrCode->generate()}}" /></div>
                                                <small>{{$text[0]->medicine_name}}</small>
                                            </td>
                                     
                                        </tr>
                                        @endfor

                                    </tbody>
                                    </table>
                                </div>
                            </div>
                            <a class="btn btn-info" href="#" onclick="printDiv('printableArea2')">Print</a>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
  
        </div>

    </div>
@endsection
@section('jquery')
<script src="{{asset('public/assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
<script src="{{asset('public/assets/plugins/tabs/tab-content.js')}}"></script>

<script>
//    
   function printDiv($div) {
            var divToPrint=document.getElementById($div);
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            newWin.document.write('<html><head><style>@media  print { body{hight: 100%} small{font-size:8px !important}  th, td { border: 0.5px solid gray; padding:5px} td{padding:5px}}</style></head> <body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
            newWin.document.close();
            setTimeout(function(){newWin.close();},10);
        }

</script>
@endsection
