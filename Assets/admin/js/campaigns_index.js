!function(e){function t(r){if(a[r])return a[r].exports;var n=a[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,t),n.l=!0,n.exports}var a={};t.m=e,t.c=a,t.d=function(e,a,r){t.o(e,a)||Object.defineProperty(e,a,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var a=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(a,"a",a),a},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t,a){e.exports=a(1)},function(e,t){$(".datatable").DataTable({responsive:!0,order:[[0,"asc"]],columns:[{data:"name",name:"name"},{data:"status",name:"status"},{data:"actions",name:"actions",orderable:!1,searchable:!1,class:"text-center"}]}),$(".preview").on("click",function(){var e=$("#previewModal"),t=$(this).data("url");e.find(".modal-body").html('<iframe frameborder="0" height="100%" width="100%" src="'+t+'"></iframe>'),e.find(".modal-body").css("height","500px")})}]);