    
@extends('layouts.app')

@section('pageTitle','Return')

@section('content')


  <div class="row wrapper border-bottom white-bg page-heading">

            <div class="col-lg-10">

            <h2>Return Item</h2>

                <ol class="breadcrumb">
                    <li>

                        Home

                    </li>

                    <li class="active">

                        <strong>Return</strong>

                    </li>
                                      
                </ol>

            </div>

        </div>
       @include('layouts.alert')
       @include('layouts.deletemodal')

        <div class="wrapper wrapper-content  animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                                     
                            <h4>Return Items</h4> 
                            
                        </div>
                        
                        <div class="ibox-content">

                             @if (!can('returns.post'))

                                @if ($returns->status == 0)

                                    <div class="btn-group">
                                         <button type="button" class="btn btn-success" onclick="confirmPost('{{$returns->id}}'); return false;" id="post-btn"><i class="fa fa-check">&nbsp;</i>Post&nbsp; </button>
                                    </div>

                                @endif

                            @endif

                            <a href="{{route('returns.print',$returns->id)}}" class="btn btn-primary btn-print"><i class="fa fa-print">&nbsp;</i>Print</a> 

                     
                            
                            <div class="form-horizontal m-t-md">

                            {!! Form::model($returns, ['route' => ['returns.update', $returns->id]]) !!}

                                @include('pages.warehouse.return._form_edit')
                                  
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>     


@endsection



@section('scripts')

<script src="/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

<script src="/js/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){

            var _id = $('#returns_id').val();

              $.ajax({  
                    url:  '{{ url('returns/return-items') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { _token: "{{ csrf_token() }}",
                    id:_id}, 
                    success:function(results){
                    
                                  //showlist

                        for(var i=0 ;i<=results.length;i++) {

                            $('#dTable-receive-item-table tbody').append("<tr><td><input type='text' name='return_id[]' class='form-control input-sm text-center return_id' size='3'  value="+ results[i].id +" readonly/></td><td>"+ results[i].item_name +"</td><td>"+ results[i].unit +"</td><td><input type='text' name='item_quantity[]' class='form-control input-sm text-center item_quantity' size='3'  value="+ results[i].item_quantity +" readonly id ='item_quantity'/></td><td><input type='text' name='return_qty[]' class='form-control input-sm text-center return_qty' size='4' value="+ results[i].return_quantity +" id ='return_qty'></td></tr>");

                            }
         
                    }
            });
    });  
 

        $('.date').datepicker({
            autoclose: true,
            format:'yyyy-mm-dd'
         });

        $('.chosen-select').chosen({width: "100%"});

        $(document).ready(function(){
            $('#btn-close').on('click', function(){
                document.location.href="/returns"; 
            });
        });

       
                // compare input quanity
        $('#dTable-receive-item-table').on('keyup','.return_qty',function(e){
        //compute price
        var _returnQty = parseFloat($(this).closest( 'tr ').find( '#return_qty' ).val());
        var _itemQty = parseFloat($(this).closest( 'tr' ).find( '#item_quantity' ).val());

             if ( _returnQty > _itemQty ){

                    toastr.warning('the return quantity is too much','Warning');
                    $(this).closest('tr').find( '#return_qty' ).val( '0' )
                    return false;
             } 
        
        }); 

    

        // allow only numeric with decimal
        $("._received_qty").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
         $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
        });

        
         $(document).on("keyup", "._discount_input", function () {

                        if (var_discount == 0 || var_discount == null){

                            var i_total_amount = 0;

                            $( "#dTable-receive-item-table tbody > tr" ).each( function() {
                                    var $row = $( this );        
                                    var _subtotal = $row.find( "._total_amount" ).val();
                
                                    i_total_amount += parseFloat( ('0' + _subtotal).replace(/[^0-9-\.]/g, ''), 10 );
                                   
                            });

                             i_total_amount = i_total_amount.toFixed(2);
                            $('input[name="grand_total_amount"]').val(  i_total_amount  );

                        }   
      
                var var_discount = $( "._discount_input" ).val();
                var var_gtotal = $( "._grand_total_amount" ).val();

                //var_discount =parseFloat( ('0' + var_discount).replace(/[^0-9-\.]/g, ''), 10 );
                //var_gtotal =parseFloat( ('0' + var_gtotal).replace(/[^0-9-\.]/g, ''), 10 );

                var var_Gtotal_amount = 0.00;
           
                    var_Gtotal_amount = var_gtotal - var_discount;

                    var_Gtotal_amount = var_Gtotal_amount.toFixed(2);
                    
                    $('input[name="grand_total_amount"]').val(  var_Gtotal_amount  );


              
            });


        function confirmPost(data,model) {   
         $('#confirmPost').modal({ backdrop: 'static', keyboard: false })
            .on('click', '#post-btn', function(){
                $(this).attr("disabled","disabled");
                document.location.href="/returns/post/"+data;
            });
        }

        
  

</script>

@endsection
