!function(e){var t={};function a(r){if(t[r])return t[r].exports;var n=t[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,a),n.l=!0,n.exports}a.m=e,a.c=t,a.d=function(e,t,r){a.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:r})},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="",a(a.s=8)}({8:function(e,t,a){e.exports=a(9)},9:function(e,t){$(".datatable").DataTable({processing:!0,serverSide:!0,ajax:pagination_url,responsive:!0,order:[[0,"desc"]],columns:[{data:"created_at",name:"created_at"},{data:"email",name:"email"},{data:"actions",name:"actions",orderable:!1,searchable:!1,class:"text-center"}]})}});