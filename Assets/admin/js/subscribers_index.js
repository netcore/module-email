!function(e){function t(r){if(a[r])return a[r].exports;var n=a[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,t),n.l=!0,n.exports}var a={};t.m=e,t.c=a,t.d=function(e,a,r){t.o(e,a)||Object.defineProperty(e,a,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var a=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(a,"a",a),a},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=8)}({8:function(e,t,a){e.exports=a(9)},9:function(e,t){$(".datatable").DataTable({processing:!0,serverSide:!0,ajax:pagination_url,responsive:!0,order:[[1,"desc"]],columns:[{data:"email",name:"email"},{data:"created_at",name:"created_at"},{data:"actions",name:"actions",orderable:!1,searchable:!1,class:"text-center"}]})}});