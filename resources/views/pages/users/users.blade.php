@extends('layouts.app')
@section('header') 
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Users') }}
    </h2>
@endsection
@section('content') 
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{ __('Users') }}                    
                    <x-app-icon-link :href="route('users.create')" class="float-right">
                       <i class="fas fa-plus fa-fw"></i>
                    </x-app-icon-link>
                </div>
            </div>

            <!--Card-->
             <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">             
                <table id="dt_users" data-id="dt_users" class="stripe hover" style="width:100%; padding-top: 1em;  padding-bottom: 1em;" data-callback="reint_dt_users_js">
                <thead>
                <tr>
                  <th data-priority="1">S.no</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Created at</th>
                  <th>Action</th>
                </tr>
                </thead>
              </table>               
            </div>
            <!--/Card-->
        </div>
    </div>
@endsection
@section('footer_script') 
<script>    
var setOptions = {
    zeroRecord:'No Record',
    data:{}
};

initDataTable('dt_users',"{{ route('dt.users') }}",setOptions);

function success_reint_dt_users_js(){
  initTwModal();
}

function reinit_dt(){  
  $('#dt_users').dataTable().fnClearTable();  
}
</script>
@endsection
@section('modals')
@include('modals.modal')
@endsection