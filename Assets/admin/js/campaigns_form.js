!function(e){function t(r){if(a[r])return a[r].exports;var s=a[r]={i:r,l:!1,exports:{}};return e[r].call(s.exports,s,s.exports,t),s.l=!0,s.exports}var a={};t.m=e,t.c=a,t.d=function(e,a,r){t.o(e,a)||Object.defineProperty(e,a,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var a=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(a,"a",a),a},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=2)}([,,function(e,t,a){e.exports=a(3)},function(e,t){new Vue({el:"#emailApp",data:{except:[],filters:filters,values:{},receivers:"all-users"},created:function(){var e=this;jQuery.each(this.filters,function(t,a){var r=[];jQuery.each(a.values,function(e,t){r.push({id:e,text:t})}),a.values=r,"select"===a.type?e.values[t]="":"multi-select"===a.type?e.values[t]=[]:"from-to"===a.type&&(e.values[t]={from:"",to:""})})},methods:{searchReceivers:function(){$(".search-table").DataTable().destroy(),$(".search-table").DataTable({processing:!0,serverSide:!0,responsive:!0,ajax:{url:search_url,type:"POST",data:{receivers:this.receivers,filters:this.values}},columns:[{data:"checkbox"},{data:"email",name:"email"}],columnDefs:{orderable:!1,targets:0}})},changeReceivers:function(){$(".search-table").DataTable().clear().draw()}}});var a=$("input[name=except]"),r=[];$(".search-table").DataTable({columnDefs:{orderable:!1,targets:0}}),$(document).on("change",".except",function(){var e=$(this).val();$(this).is(":checked")?function(e){for(var t=0;-1!==(t=r.indexOf(e,t));)r.splice(t,1);a.val(JSON.stringify(r))}(e):function(e){r.push(e),a.val(JSON.stringify(r))}(e)}),receivers_url&&$(".receivers-table").DataTable({processing:!0,serverSide:!0,ajax:receivers_url,responsive:!0,order:[[0,"asc"]],columns:[{data:"email",name:"email"},{data:"sent",name:"is_sent",class:"text-center"},{data:"actions",name:"actions",orderable:!1,searchable:!1,class:"text-center"}]}),$(".summernote").summernote({height:300,focus:!0,toolbar:[["style",["bold","italic","underline","clear"]],["fontsize",["fontsize"]],["color",["color"]],["style",["style"]],["para",["ul","ol","paragraph"]],["height",["height"]],["insert",["picture","link"]]],fontSizes:["10","11","12","13","14","15","16","17","18","19","20","21","22","23","24"]})}]);