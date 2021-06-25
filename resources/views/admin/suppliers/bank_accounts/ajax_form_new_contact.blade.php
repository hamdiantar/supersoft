<div class="row bank-account-{{$index}}">

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">{{__('Bank Name')}}</label>
                <span class="asterisk" style="color: #ff1d47"> * </span>
                <input type="text" name="bankAccount[{{$index}}][bank_name]"   class="form-control">
            </div>
            {{input_error($errors,'bankAccount.'.$index.'.bank_name')}}
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">{{__('Account Name')}}</label>
                <span class="asterisk" style="color: #ff1d47"> * </span>
                <input type="text" name="bankAccount[{{$index}}][account_name]"  class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">{{__('Branch Name')}} :</label>
                <span class="asterisk" style="color: #ff1d47"> * </span>
                <input type="text" name="bankAccount[{{$index}}][branch]"  class="form-control">
            </div>
        </div>
    </div>

   <div class="row">
       <div class="col-md-3">
           <div class="form-group">
               <label for="exampleInputPassword1"> {{__('Account Number')}} : </label>
               <span class="asterisk" style="color: #ff1d47"> * </span>
               <input ype="text" name="bankAccount[{{$index}}][account_number]"  class="form-control" style=" height: 45px;">
           </div>
           {{input_error($errors,'bankAccount.'.$index.'.account_number')}}
       </div>

       <div class="col-md-3">
           <div class="form-group">
               <label for="exampleInputEmail1">{{__('IBAN')}} :</label>
               <span class="asterisk" style="color: #ff1d47"> * </span>
               <input type="text" name="bankAccount[{{$index}}][iban]"  class="form-control">
           </div>
       </div>

       <div class="col-md-3">
           <div class="form-group">
               <label for="exampleInputEmail1">{{__('Swift Code')}} :</label>
               <span class="asterisk" style="color: #ff1d47"> * </span>
               <input type="text" name="bankAccount[{{$index}}][swift_code]"  class="form-control">
           </div>
       </div>

       <div class="col-md-3">
           <div class="form-group">
               <button class="btn btn-sm btn-danger" type="button"
                       onclick="deleteBankAccount('{{$index}}')"
                       id="delete-div-" style="margin-top: 31px;">
                   <li class="fa fa-trash"></li>
               </button>
           </div>
       </div>
   </div>
</div>
<hr>
