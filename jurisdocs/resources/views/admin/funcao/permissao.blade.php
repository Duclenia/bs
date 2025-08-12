@extends('admin.layout.app')
@section('title','Função')
@push('style')

@endpush
@section('content')
   <div class="">
     @component('component.heading' , [

     'page_title' => 'Função: '. $funcao. ' - Permissão',
    'action' => route('funcao.index') ,
    'text' => 'Voltar'
     ])
    @endcomponent
      <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                  <form action="{{ route('permission.update',$role_id) }}"  method="post" name="product_type_attribute_form" id="product_type_attribute_form" enctype="multipart/form-data" class="m-form m-form--fit m-form--label-align-right">

                                      @csrf @method('PUT')
                <div class="x_panel">
                  <div class="x_content">

                    <table class="table">
                        <thead>
                          <tr>
                            <th width="30%">Menu</th>
                            <th width="28%">Sub Menu</th>

                            <th width="10%"><input class="all_view" type="checkbox">&nbsp; Ver </th>
                            <th width="12%"> <input class="all_add" type="checkbox">&nbsp; Adicionar</th>
                            <th width="10%"> <input class="all_edit" type="checkbox">&nbsp; Editar</th>
                            <th width="10%"> <input class="all_delete" type="checkbox">&nbsp; Eliminar</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="tr_permition">
                            <td>Dashboard</td>

                            <td>-</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="1" name="permission[]" {{ ($permissions_array->contains('1')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              -
                            </td>
                            <td>
                              -
                            </td>
                            <td>
                              -
                            </td>
                          </tr>
                          <tr  class="tr_permition">
                            <td>Cliente</td>

                            <td>-</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand "><input class="permition_view" type="checkbox" value="2" name="permission[]" {{ ($permissions_array->contains('2')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="3" name="permission[]" {{ ($permissions_array->contains('3')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="4" name="permission[]" {{ ($permissions_array->contains('4')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="5" name="permission[]" {{ ($permissions_array->contains('5')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          <tr  class="tr_permition">
                            <td>Tarefa</td>

                            <td>-</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="6" name="permission[]" {{ ($permissions_array->contains('6')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="7" name="permission[]" {{ ($permissions_array->contains('7')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="8" name="permission[]" {{ ($permissions_array->contains('8')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="9" name="permission[]" {{ ($permissions_array->contains('9')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                          <tr  class="tr_permition">
                            <td>Fornecedor</td>

                            <td>-</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="10" name="permission[]" {{ ($permissions_array->contains('10')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="11" name="permission[]" {{ ($permissions_array->contains('11')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="12" name="permission[]" {{ ($permissions_array->contains('12')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="13" name="permission[]" {{ ($permissions_array->contains('13')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                            <tr  class="tr_permition">
                            <td>Processo</td>

                            <td>-</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand">
                               <input class="permition_view" type="checkbox" value="14" name="permission[]" {{ ($permissions_array->contains('14')) ? 'checked' : '' }}>
                               <div class="m-form__heading-title"></div>
                               <span></span>
                           </label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="15" name="permission[]" {{ ($permissions_array->contains('15')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="16" name="permission[]" {{ ($permissions_array->contains('16')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                               -
                            </td>
                          </tr>
                          <tr  class="tr_permition">
                            <td>Agendamento</td>

                            <td>-</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="17" name="permission[]" {{ ($permissions_array->contains('17')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="18" name="permission[]" {{ ($permissions_array->contains('18')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="19" name="permission[]" {{ ($permissions_array->contains('19')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                                -
                            </td>
                          </tr>
                          
                          <tr  class="tr_permition">
                            <td>Planos</td>

                            <td>-</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand "><input class="permition_view" type="checkbox" value="96" name="permission[]" {{ ($permissions_array->contains('96')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="94" name="permission[]" {{ ($permissions_array->contains('94')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="97" name="permission[]" {{ ($permissions_array->contains('97')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            
                            <td>-</td>
                          </tr>
                          
                          
                          <tr  class="tr_permition">
                              <td>Subscri&ccedil;&otilde;es</td>

                            <td>-</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand "><input class="permition_view" type="checkbox" value="98" name="permission[]" {{ ($permissions_array->contains('98')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                           
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="95" name="permission[]" {{ ($permissions_array->contains('95')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="99" name="permission[]" {{ ($permissions_array->contains('99')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            
                            <td>-</td>
                          </tr>
                          
                          <tr  class="tr_permition">
                            <td>Despesa</td>

                            <td>Tipo de despesa</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="20" name="permission[]" {{ ($permissions_array->contains('20')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="21" name="permission[]" {{ ($permissions_array->contains('21')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="22" name="permission[]" {{ ($permissions_array->contains('22')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="23" name="permission[]" {{ ($permissions_array->contains('23')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                          <tr  class="tr_permition">
                            <td></td>

                            <td>Despesa</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="24" name="permission[]" {{ ($permissions_array->contains('24')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="25" name="permission[]" {{ ($permissions_array->contains('25')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="26" name="permission[]" {{ ($permissions_array->contains('26')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="27" name="permission[]" {{ ($permissions_array->contains('27')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                             
                          <tr  class="tr_permition">
                            <td>Receitas</td>

                            <td>Servi&ccedil;os</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="59" name="permission[]" {{ ($permissions_array->contains('59')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="60" name="permission[]" {{ ($permissions_array->contains('60')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="61" name="permission[]" {{ ($permissions_array->contains('61')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="62" name="permission[]" {{ ($permissions_array->contains('62')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>

                           <tr  class="tr_permition">
                            <td></td>

                            <td>Factura</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="28" name="permission[]" {{ ($permissions_array->contains('28')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="29" name="permission[]" {{ ($permissions_array->contains('29')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="30" name="permission[]" {{ ($permissions_array->contains('30')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="31" name="permission[]" {{ ($permissions_array->contains('31')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>

                            <tr  class="tr_permition">
                                <td>Configura&ccedil;&otilde;es</td>

                            <td>Tipo de processo</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand">
                               <input class="permition_view" type="checkbox" value="32" name="permission[]" {{ ($permissions_array->contains('32')) ? 'checked' : '' }}>
                               <div class="m-form__heading-title"></div>
                               <span></span>
                           </label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="33" name="permission[]" {{ ($permissions_array->contains('33')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="34" name="permission[]" {{ ($permissions_array->contains('34')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="35" name="permission[]" {{ ($permissions_array->contains('35')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                            <tr  class="tr_permition">
                            <td></td>

                            <td>Crime enquadramento</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="80" name="permission[]" {{ ($permissions_array->contains('80')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="83" name="permission[]" {{ ($permissions_array->contains('83')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="84" name="permission[]" {{ ($permissions_array->contains('84')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="85" name="permission[]" {{ ($permissions_array->contains('85')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                          <tr  class="tr_permition">
                            <td></td>

                            <td>Crime Sub-enquadramento</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="81" name="permission[]" {{ ($permissions_array->contains('81')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="89" name="permission[]" {{ ($permissions_array->contains('89')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="86" name="permission[]" {{ ($permissions_array->contains('86')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="87" name="permission[]" {{ ($permissions_array->contains('87')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                          <tr  class="tr_permition">
                            <td></td>

                            <td>Tipo de crime</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="76" name="permission[]" {{ ($permissions_array->contains('76')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="79" name="permission[]" {{ ($permissions_array->contains('79')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="77" name="permission[]" {{ ($permissions_array->contains('77')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="78" name="permission[]" {{ ($permissions_array->contains('78')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                          <tr  class="tr_permition">
                            <td></td>

                            <td>&Oacute;rg&atilde;o judici&aacute;rio</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="90" name="permission[]" {{ ($permissions_array->contains('90')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="93" name="permission[]" {{ ($permissions_array->contains('93')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="91" name="permission[]" {{ ($permissions_array->contains('91')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="92" name="permission[]" {{ ($permissions_array->contains('92')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                            <tr  class="tr_permition">
                            <td></td>

                            <td>Tribunal</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="40" name="permission[]" {{ ($permissions_array->contains('40')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="41" name="permission[]" {{ ($permissions_array->contains('41')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="42" name="permission[]" {{ ($permissions_array->contains('42')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="43" name="permission[]" {{ ($permissions_array->contains('43')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                          <tr  class="tr_permition">
                            <td></td>

                            <td>Sec&ccedil;&atilde;o</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="63" name="permission[]" {{ ($permissions_array->contains('63')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="64" name="permission[]" {{ ($permissions_array->contains('64')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="65" name="permission[]" {{ ($permissions_array->contains('65')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="66" name="permission[]" {{ ($permissions_array->contains('66')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                          
                          <tr  class="tr_permition">
                            <td></td>

                            <td>Bairro</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="71" name="permission[]" {{ ($permissions_array->contains('71')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="72" name="permission[]" {{ ($permissions_array->contains('72')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="73" name="permission[]" {{ ($permissions_array->contains('73')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="74" name="permission[]" {{ ($permissions_array->contains('74')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                          
                          <tr  class="tr_permition">
                            <td></td>

                            <td>Interv.designa&ccedil;&atilde;o</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="67" name="permission[]" {{ ($permissions_array->contains('67')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="68" name="permission[]" {{ ($permissions_array->contains('68')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="69" name="permission[]" {{ ($permissions_array->contains('69')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="70" name="permission[]" {{ ($permissions_array->contains('70')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                          
                            <tr  class="tr_permition">
                            <td></td>

                            <td>Estado do processo</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand">
                               <input class="permition_view" type="checkbox" value="44" name="permission[]" {{ ($permissions_array->contains('44')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="45" name="permission[]" {{ ($permissions_array->contains('45')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="46" name="permission[]" {{ ($permissions_array->contains('46')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="47" name="permission[]" {{ ($permissions_array->contains('47')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                            <tr  class="tr_permition">
                            <td></td>

                            <td>Juiz</td>
                            <td>
                           <label class="m-checkbox m-checkbox--brand"><input class="permition_view" type="checkbox" value="48" name="permission[]" {{ ($permissions_array->contains('48')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                           </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_add" type="checkbox" value="49" name="permission[]" {{ ($permissions_array->contains('49')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input  class="permition_edit" type="checkbox" value="50" name="permission[]" {{ ($permissions_array->contains('50')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_delete" type="checkbox" value="51" name="permission[]" {{ ($permissions_array->contains('51')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                          </tr>
                            
                            <tr>
                            <td></td>

                            <td>Configura&ccedil;&atilde;o Geral</td>
                            <td>
                              -
                           </td>
                            <td>
                              -
                            </td>
                            <td>
                              <label class="m-checkbox m-checkbox--brand"><input class="permition_edit" type="checkbox" value="58" name="permission[]" {{ ($permissions_array->contains('58')) ? 'checked' : '' }}><div class="m-form__heading-title"></div><span></span></label>
                            </td>
                            <td>
                             -
                            </td>
                          </tr>

                        </tbody>
                      </table>
                      
                  </div>

                </div>
                   <div class="form-group pull-right">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                          <a href="{{ route('funcao.index')  }}" class="btn btn-danger">Cancelar</a>

                          <button type="submit" class="btn btn-success"><i class="fa fa-save" id="show_loader"></i>&nbsp;Guardar</button>
                        </div>
                  </div>
                </form>
              </div>
            </div>
</div>
@endsection

@push('js')
    <script src="{{asset('assets/js/funcao/permition.js')}}"></script>
@endpush
