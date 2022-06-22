var upload_config = {
	maxqueuesize: {},
	queuesize: {},
	upcallback: {},
	upcallbackoptions: {},
    _upcallback: function()
    {
        var uc = this.upcallback;
        if (uc) {
            var callback = $.Callbacks();
            callback.add(uc);
            callback.fire(this.upcallbackoptions);
        }
    },
    setMaxqueuesize: function(limit,id){
    	//id là id của upload file
    	this.maxqueuesize[id] = limit;
    },
    getMaxqueuesize: function(id){return this.maxqueuesize[id];},
    setQueuesize: function(size,id){this.queuesize[id]=size},
    getQueuesize: function(id){return this.queuesize[id];},
    reduceQueuesize: function(id){
    	var qz = upload_config.getQueuesize(id);
    	if(qz-1<=0) $('#'+id).parents('.boxuploadfile').find('.valuebox').addClass('hidden2');
    	upload_config.setQueuesize(qz-1,id);
    	$('#'+id).uploadify('disable',false);
    },
    increaseQueuesize: function(id){upload_config.setQueuesize(upload_config.getQueuesize(id)+1);}
};


function test(options){
	console.log(options);
}

//var callbacks = $.Callbacks();
//callbacks.add( fn1 );
//callbacks.fire(new Array('1','2','3'));
//
//function fn1( value ) {
//    console.log( value );
//}
//
//function fn2( value ) {
//    console.log("fn2 says: " + value);
//    return false;
//}