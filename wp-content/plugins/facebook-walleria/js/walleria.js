window.fbAsyncInit= function() {

      FB.init({
      appId      : fwpgsettings.fwpg_appId, // App ID from the App Dashboard
      channelUrl : '', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });
    jQuery(document).ready(function($){ 
         
     _walleribox_images();
        var fbroot=$('#fb-root')
        
        if(fbroot.length==0){
            $('body').append('<div id="fb-root"></div>')
        }    
        //who is logged in
        FB.getLoginStatus(function(response) {
            if (response.authResponse) {
   
                userID =response.authResponse.userID
                token=response.authResponse.accessToken
                curruser=response.authResponse.userID
                $('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture?access_token='+token)
                $('.fp-CommenterImg').show()
            }else{
                 $('.fp-CommenterImg').hide();
            }
        })
 

//====================Load comments===========================
$('.fp-ProfileStream').on('click','.fp-ViewPrevious',function(){
   
    var postid=$(this).attr('data-id')
    var counts=$(this).attr('data-count')
    //counts=JSON.parse(counts)
    //var total=counts.total
    //var shown=counts.shown
    //var page=$(this).attr('data-page')
    var args=$(this).data('args')
    //var limit=50
    //var offset=(parseInt(page)-1)*limit
    var obj=$(this).closest('.fp-CommentsBar').siblings('.fp-CommentsBody')
  
      get_comments(obj,postid,args)
       
    return false    
})

function get_comments(obj,postid,args){
  
 obj.siblings('.fp-CommentsBar').find('.fp-ViewPrevious').append('<span class="fp-LoaderImg" style="margin: 0 5px"><img src="'+fwpgsettings.fwpg_url+'/images/loader_small.gif" /></span>')
  
    $.post(fwpgsettings.ajaxurl,{
        'action':'getcomments',
        'postid':postid, 
        'args':args
    }, function(comments) { 
        comments=$.parseJSON(comments)
        obj.append(comments.data)
       // obj.siblings('.fp-CommentsBar').find('.fp-ViewPrevious').attr('data-args',comments.paging)
        if(comments.paging!=""){
        obj.siblings('.fp-CommentsBar').find('.fp-ViewPrevious').data('args',comments.paging.cursors)
        }else{
         obj.siblings('.fp-CommentsBar').remove();    
        }
        obj.siblings('.fp-CommentsBar').find(".fp-LoaderImg").remove()
    });
}
function get_few_comments(obj,postid,limit,offset){
   
    obj.siblings('.fp-CommentsBar').find('.fp-ViewPrevious').append('<span class="fp-LoaderImg" style="margin: 0 5px"><img src="'+fwpgsettings.fwpg_url+'/images/loader_small.gif" /></span>')
    var li=obj.children('.fp-CommentItem');
    $.post(fwpgsettings.ajaxurl,{
        'action':'getcomments',
        'postid':postid,         'limit':limit,
        'offset':offset
    }, function(data) {
        obj.prepend(data)

        obj.siblings('.fp-CommentsBar').find('.fp-ViewPrevious').text(fwpgsettings.viewprev)
        li.remove();
        obj.siblings('.fp-CommentsBar').find(".fp-LoaderImg").remove()
        obj.siblings('.fp-CommentsBar').remove()
    });
 } 
/**
 * Load more wall
 */
$('.fp-WallContainer .fp-BottomBar').on('click',function(){
    var obj=$(this);
    var id =$(this).attr('data-id');
    var args=$(this).attr('data-args');
    var type=$(this).attr('data-type');
    var page=parseInt(obj.attr('data-page'))+1;
    var unix =$(this).siblings('.fp-ProfileStream').find('.fp-StreamWrapper:last').attr('data-href')
    var limit=$(this).attr('data-limit')
    var cancomment=$(this).attr('data-cancomment')
    var loader=$('<img src="'+ fwpgsettings.fwpg_url+'/images/loader_small.gif" />');
    var img=obj.find('.fp-Loadmore').find('img')
   
    img.replaceWith(loader);
    $.post(fwpgsettings.ajaxurl,{
        'action':'getstream',
        'id':id, 
        'args':args,
        'page':page,
        'type':type,
        'cancomment':cancomment
    }, function(posts) {
      posts=$.parseJSON(posts)
       obj.siblings('.fp-ProfileStream').append(posts.data)
       obj.attr('data-args',posts.paging)
       //If paging exists show or hide mo
       if(posts.paging==""){
           obj.remove();
       }else{
           obj.attr('data-page',page);
       }
        loader.replaceWith(img);
       FB.getLoginStatus(function(response) {
            if (response.authResponse) {
   
                userID =response.authResponse.userID
                token=response.authResponse.accessToken
                curruser=response.authResponse.userID
                $('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture?access_token='+token)
            }
        })
        _walleribox_images();
    })
    
    
    return false;
})

 //========================================handle comment box focus========================//
$(".fp-FooterItemWrapper").on('click','.fp-PreComment',function(){
    var id=$(this).attr('data-id')
    $(this).text("")
    var obj=$(this),
        pholder=$('<span><img src="'+fwpgsettings.fwpg_url+'/images/loader_small.gif" /></span>');
    var retmsg
    FB.getLoginStatus(function(response) {
        if (response.authResponse) {
            token=response.authResponse.accessToken
            curruser=response.authResponse.userID
            if(typeof curruser !='undefined'){
                obj.closest(".fp-ImgBlockWrapper").find(".fp-CommenterImg").css('display','block')
            }
            obj.keyup(function(e){
 
                e.preventDefault()
                if(e.keyCode == 13){
                    var ecomment=obj.val();
                      
                    if(ecomment !=""){  
                        obj.before(pholder);
                       obj.hide();
                        FB.api(id+'/comments', 'post', {
                            access_token:token,
                            xfbml: true,
                            message:ecomment
                                    
                        },function(result) {
          
                            if(result!=""){ 
                                //retrieve the comment & post it
                                $.getJSON('https://graph.facebook.com/'+result.id+'?access_token='+token+'&callback=?',function(data){
                                    var profphoto='https://graph.facebook.com/'+userID+"/picture?access_token="+token
                                    var comment='<div class="fp-FooterItemWrapper fp-CommentItem"><div class="fp-ImgBlockWrapper fp-Clear"><a href="http://www.facebook.com/profile.php?id='+data.from.id+'" class="fp-BlockImage"><img class=" fp-ProfilePhotoMedium" src="'+profphoto+'" width=32 height=32/></a><div class="fp-ImgBlockContent" data-id="'+data.id+'"><div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id='+data.from.id+'">'+data.from.name+'</a></div><div class="fp-CommentSpan ">'+data.message+'</div><span data-time="'+data.created_time+'" class="fp-DateRep">'+output_time(333,data.created_time )+'</span></div></div></div>'
        
                                    //insert comment
                                    obj.closest('.fp-CommentBox').before(comment)
                                    obj.val(fwpgsettings.writecomment).blur()
                                    pholder.remove();
                                    obj.show();
                                })
                                retmsg=fwpgsettings.composted
          
                            }else{
                                retmsg='<div class="fp-Error">'+fwpgsettings.error+'</div>'
                            }
                        //obj.replaceWith(retmsg)
                        });
                    }
                }
            });
        }
        else{
            //obj.blur()
            FB.login(function(response){
                if(response.authResponse !=null){  
   
                    token=response.authResponse.accessToken
                    curruser=response.authResponse.userID
                    $('.fp-CommenterImg').attr('src','https://graph.facebook.com/'+curruser+'/picture')
                    if(typeof curruser !='undefined'){
                        obj.closest(".fp-ImgBlockWrapper").find(".fp-CommenterImg").css('display','block')
                    }
                   
                    obj.blur()
                    token=""
                    curruser=""
                    }
                },{
                scope: 'publish_stream'
            })
    
    }
    })

})

/**
 *  Load more Albums
 */
$('.fp-container .right-scroll').on('click',function(){
   var obj=$(this),
   id=obj.closest('.fp-container').attr('data-id'),
   size=obj.attr('data-size'),
   page=parseInt(obj.attr('data-page'))+1,
   excl=obj.closest('.fp-container').attr('data-excl'),
   args=obj.data('cursors')
   obj.closest('.fp-container').append('<span class="fp-AlbumLoader" style=""><img src="'+fwpgsettings.fwpg_url+'/images/loader.gif" /></span>')
   obj.siblings ('.fp-AlbumContainer').css({'opacity':'0.4'})
    $.post(fwpgsettings.ajaxurl,{
        'action':'getalbums',
        'id':id,
        'args':args,
        'page':page,
        'excl':excl,
        'size':size
    }, function(albums){
        albums=$.parseJSON(albums);
        obj.closest('.fp-container').find('.fp-AlbumContainer').html(albums.data)
        obj.siblings ('.fp-AlbumContainer').css({'opacity':'1'})
        obj.closest('.fp-container').find('.fp-AlbumLoader').remove()
        obj.siblings('.left-scroll').attr('data-page',page)
        obj.attr('data-page',page)
        //If there is next
       if(albums.paging.next !=""){
       obj.attr('data-next',albums.paging.next)
       obj.show();
    }else{
        obj.hide();
    }
     if(albums.paging.previous !=""){
       obj.closest('.fp-container').attr('data-previous',albums.paging.previous)
       obj.siblings('.left-scroll').show();
       }else{
        obj.siblings('.left-scroll').hide();
    }
       
    })     
    
    
})
/**
 *  Load more Albums
 */
$('.fp-container .left-scroll').on('click',function(){
   var obj=$(this),
   size=obj.attr('data-size'),
   id=obj.closest('.fp-container').attr('data-id'),
   page=parseInt(obj.attr('data-page'))-1,
   excl=obj.closest('.fp-container').attr('data-excl'),
   args=obj.data('cursors')
    obj.siblings ('.fp-AlbumContainer').css({'opacity':'0.4'})
    obj.closest('.fp-container').append('<span class="fp-AlbumLoader"><img src="'+fwpgsettings.fwpg_url+'/images/loader.gif" /></span>')
    $.post(fwpgsettings.ajaxurl,{
        'action':'getalbums',
        'id':id,
        'args':args,
        'page':page,
        'excl':excl,
        'size':size
    }, function(albums){
        albums=$.parseJSON(albums);
       obj.closest('.fp-container').find('.fp-AlbumContainer').html(albums.data)
       obj.siblings ('.fp-AlbumContainer').css({'opacity':'1'})
       obj.closest('.fp-container').find('.fp-AlbumLoader').remove()
       obj.siblings('.right-scroll').attr('data-page',page)
       obj.attr('data-page',page)
       //If there is next
       if(albums.paging.next !=""){
       obj.attr('data-next',albums.paging.next)
       obj.siblings('.right-scroll').show();
    }else{
        obj.siblings('.right-scroll').hide();
    }
     if(albums.paging.previous !=""){
       obj.closest('.fp-container').attr('data-previous',albums.paging.previous)
       obj.show();
       }else{
         obj.hide();
    }
    })     
    
    
})

 $('.fp-container').on('mouseenter','.fp-albThumbLink',function(){
var obj=$(this)
    var intid=setInterval(function(){
    
    var spans=obj.find('.fp-albThumbWrap'),
    firstSpan=$(spans[0]),
    firstSpanClone=firstSpan.clone();
    firstSpan.fadeOut('slow',function(){
        $(this).remove();
    })
    //firstSpan.remove();
    firstSpanClone.hide();
    obj.append(firstSpanClone)
    firstSpanClone.fadeIn('slow')
    },2000)
    
 obj.mouseleave(function(){
     clearInterval(intid)
 })

})
 //===========================RENDER PHOTO THUMBNAILS===========================================

        $('.fp-container').on('click','.fp-albThumbLink,.fp-DescLink ',function(){
            if($(this).attr('data-click')==0){
                $(this).attr('data-click',1)

            } 
                //get id 
                var id=$(this).data('id'),
                obj=$(this),
                size=obj.closest('.fp-container').attr('data-size'),
                toggle=obj.closest('.fp-container').attr('data-toggle'),
                args=obj.closest('.fp-container').attr('data-next'),
                title=obj.closest('.fp-mainAlbWrapper').find('.fp-DescLink ').text(),
                pcont=obj.closest('.fp-container').find('.fp-PhotoContainer'),
                cont=obj.closest('.fp-container').find('.fp-photoContainerBody'),
                total =obj.attr('data-count'),
                paging=obj.closest('.fp-container').attr('data-paging')
                pcont.find('.fp-AlbumHeader').html(title)
                pcont.removeClass('fp-Hide')
                pcont.attr('data-albumid',id)
                cont.empty().show();
                cont.html('<div class="fp-loader"><img src="'+fwpgsettings.fwpg_url+'/images/loader.gif" /></div>')
                $.post(fwpgsettings.ajaxurl,{
                    'action':'getphotos', 
                    'id':id,
                    'page':1,
                    'size':size
                }, function(photos) {
                    photos=$.parseJSON(photos)
                    //hide loader
                    cont.find('.fp-loader').remove();
                    
                    cont.append(photos.data)
                    if(photos.paging.next!=""){
                    obj.closest('.fp-container').attr('data-next',photos.paging.next)
                    
                    }
                    _walleribox_images();
                })//close post
        
                //cont.append('<div class="fp-Clear"></div>');
                //render page boxes
     if(toggle){
                obj.closest('.fp-albumContainerWrap').fadeOut('slow',function(){
                obj.closest('.fp-albumContainerWrap').siblings('.fp-PhotoContainer').show()
                obj.closest('.fp-albumContainerWrap').siblings('.fp-PhotoContainer').find('.fp-ShowAlbums').show()
                })
     }
            //})
            //}
            return false
        })//close live
        //
          //=====================Handle toggle=================================   
  
        $('.fp-ShowAlbums').on('click','.fp-BackToAlbums',function(){
       $(this).closest('.fp-PhotoContainer').find('.fp-photoContainerBody').slideUp('fast',function(){
        $(this).children().remove();
        $(this).closest('.fp-PhotoContainer').addClass('fp-Hide');
        $(this).closest('.fp-container').find('.fp-albumContainerWrap').show();
        $(this).parent('.fp-ShowAlbums').hide()
    })
      
        
        })
 //==================Load More photos=========================
 $('.fp-PhotoContainer').on('click',' .fp-BottomBar',function(){
     
     //get id 
                
                var obj=$(this),
                id=$(this).attr('data-id'),
                page=parseInt(obj.attr('data-page'))+1,
                args=obj.data('args'),
                size=obj.attr('data-size'),
                limit=obj.attr('data-limit'),
                cont=obj.closest('.fp-container').find('.fp-PhotoContainer'),
                loader=$('<div class="fp-loader"><img src="'+fwpgsettings.fwpg_url+'/images/loader.gif" /></div>')
               obj.replaceWith(loader);
               $.post(fwpgsettings.ajaxurl,{
                    'action':'getphotos', 
                    'id':id, 
                    'args':args,
                    'page':page,
                    'limit':limit,
                    'size':size
                }, function(photos) {
                    photos=$.parseJSON(photos)
                  
          
                   loader.replaceWith(photos.data); 
                    obj.attr('data-page',page)
                    if(photos.paging.next!=""){
                    obj.closest('.fp-container').attr('data-next',photos.paging.next)
                    
                    }
     _walleribox_images()
 })
 })
 
 $('.fp-PhotoContainer').on('click','.fp-Remove',function(){ 
    var obj=$(this); 
    obj.closest('.fp-PhotoContainer').find('.fp-photoContainerBody').slideUp('fast',function(){
        $(this).children().remove();
        $(this).closest('.fp-PhotoContainer').addClass('fp-Hide');
        $(this).closest('.fp-container').find('.fp-albumContainerWrap').show();
    })
    return false;
 })
 
 
 //============================walleribox Widget Photos==============================//
        $('.fp-WidgetPhotoWrap').on('click','.fp-WidgetPhoto',function(e){
            e.preventDefault();
    
            var photoId = $(this).attr('data-id')
            var owner = $(this).attr('data-from')
            var href =$(this).attr('href')
            var title=$(this).attr('title')
            var name;
            var photoArray;
            var a=new Array();
    
            a[0]={
                'href':href, 
                'title':title, 
                'fbid':photoId,
                'fbowner':owner
            }
          
            var s=$(this).closest('.fp-WidgetPhotoWrap').find('.fp-WidgetPhoto')
   
            //console.log(s)
            $.each(s,function(i,item){
                if($(item).attr('data-id') != photoId){
                    a.push({
                        href:item.href, 
                        fbowner:$(item).attr('data-from'),
                        fbid:$(item).attr('data-id')
                        })
                }
            })

            var photos=$(this).closest('.fp-WidgetPhotoWrap').find('.jsondata').attr('data-json')

            var pt=$.parseJSON(photos)

            photoArray=$.merge(a,pt.reverse())

            _walleribox_image_with_data(photoArray)

            return false;
        })
        
        

/**
 * Album pagination scrolling downwards
 */
  $('.fp-albumContainerWrap').on('click','.fp-BottomBar',function(){ 
       var obj=$(this),
   id=obj.closest('.fp-container').attr('data-id'),
   excl=obj.closest('.fp-container').attr('data-excl'),
   size=obj.attr('data-size'),
   limit=parseInt(obj.data('limit')),
   page=parseInt(obj.data('page'))+1,
   args=obj.data('cursors'),
   loader=$('<img src="'+ fwpgsettings.fwpg_url+'/images/loader_small.gif" />'),
   img=obj.find('.fp-Loadmore').find('img')
   
    img.replaceWith(loader);
    $.post(fwpgsettings.ajaxurl,{
        'action':'getalbums',
        'id':id,
        'args':args,
        'page':page,
        'limit':limit,
        'excl':excl,
        'size':size
    }, function(albums){
        albums=$.parseJSON(albums);
       obj.closest('.fp-container').find('.fp-AlbumContainer').append(albums.data)
       loader.replaceWith(img);
       obj.data('page',page)
       //If there is next
       if(albums.paging.after !== undefined){ 
       obj.data('cursors',{'after':albums.paging.after})
       
    }else{
        obj.remove();
    }
    })     
    
  })
      function _walleribox_images(){
           
            if(fwpgsettings.fwpg_gallery=='Fancybox'){
                $('.fp-WallPhotoThumb, a.fp-PhotoThumbnail, .fp-WallPhoto').fancybox({
                    'width':   'fwpg_frameWidth' in fwpgsettings ?  fwpgsettings.fwpg_frameWidth: "560" ,
                    'height':    'fwpg_frameHeight'in fwpgsettings? fwpgsettings.fwpg_frameHeight:"340" ,
                    'titleShow': 'fwpg_showTitle' in fwpgsettings? true:   false  ,
                    'cyclic': 'fwpg_cyclic' in fwpgsettings?   true :  false  ,
                    'titlePosition': 'fwpg_titlePosition'in fwpgsettings? fwpgsettings.fwpg_titlePosition:'inside',
                    'padding':   'fwpg_padding'in fwpgsettings? fwpgsettings.fwpg_padding:'10' ,
                    'autoScale':  'fwpg_imageScale' in fwpgsettings?   "true" :  "false"  ,
                    'padding':   'fwpg_padding' in fwpgsettings? fwpgsettings.fwpg_padding: "10",
                    'opacity':  'fwpg_Opacity' in fwpgsettings?   "true" :  "false"  ,
                    'speedIn':   'fwpg_SpeedIn' in fwpgsettings? fwpgsettings.fwpg_SpeedIn: "300",
                    'speedOut':  'fwpg_SpeedOut' in fwpgsettings?  fwpgsettings.fwpg_SpeedOut :"300",
                    'changeSpeed':    'fwpg_SpeedChange'in fwpgsettings?  fwpgsettings.fwpg_SpeedChange: "300",
                    'overlayShow':  'fwpg_overlayShow' in fwpgsettings?"true" :  "false"  ,
                    'overlayColor':   'fwpg_overlayColor'in fwpgsettings?  fwpgsettings.fwpg_overlayColor: '#666',
                    'overlayOpacity':   'fwpg_overlayOpacity'in fwpgsettings?  fwpgsettings.fwpg_overlayOpacity: "0.3",
                    'centerOnScroll':  'fwpg_centerOnScroll' in fwpgsettings?  "true" :  "false"  ,
                    'enableEscapeButton':  'fwpg_enableEscapeButton'in fwpgsettings?   "true"  : "false"  ,
                    'showCloseButton':  'fwpg_showCloseButton'in fwpgsettings?   "true" :  "false"  ,
                    'hideOnOverlayClick': false, //'fwpg_hideOnOverlayClick'in fwpgsettings?   "true" :   "false"  ,
                    'hideOnContentClick': false, //'fwpg_hideOnContentClick' in fwpgsettings?  "true" :  "false" , 
                    //'OnStart:':'fwpg_callbackOnStart' in fwpgsettings? fwpgsettings.fwpg_callbackOnStart:  null ,
                    //'OnComplete':'fwpg_callbackOnShow'in fwpgsettings? fwpgsettings.fwpg_callbackOnShow :null,
                    'OnClosed':'fwpg_callbackOnClose'in fwpgsettings? fwpgsettings.fwpg_callbackOnClose:null
                })
            }
            if(fwpgsettings.fwpg_gallery=='PrettyPhoto'){
    
                $('.fp-WallPhotoThumb, a.fp-PhotoThumbnail, .fp-WallPhoto').prettyPhoto({
                    'default_width':   'fwpg_frameWidth' in fwpgsettings ?  fwpgsettings.fwpg_frameWidth: "560" ,
                    'default_height':    'fwpg_frameHeight'in fwpgsettings? fwpgsettings.fwpg_frameHeight:"340" ,
                    'show_title': 'fwpg_showTitle' in fwpgsettings? true:   false  ,
                    'padding':   'fwpg_padding'in fwpgsettings? fwpgsettings.fwpg_padding:'10' ,
                    'allow_resize':  'fwpg_imageScale' in fwpgsettings?   "true" :  "false"  ,
                    'opacity':  'fwpg_Opacity' in fwpgsettings?   "true" :  "false"  ,
                    'animation_speed':    'fwpg_SpeedChange'in fwpgsettings?  fwpgsettings.fwpg_SpeedChange: "300"
                });
            }
  if(fwpgsettings.fwpg_gallery=='Photoswipe'){
      var obs= $('.fp-WallPhotoThumb, a.fp-PhotoThumbnail, .fp-WallPhoto,.fp-WidgetPhoto');
      if(obs.length>0){
       $('.fp-WallPhotoThumb, a.fp-PhotoThumbnail, .fp-WallPhoto,.fp-WidgetPhoto').photoSwipe({
                      enableMouseWheel: false , enableKeyboard: false,
                      imageScaleMethod: 'fwpg_imageScale' in fwpgsettings?   'fit' :  "fitNoUpscale"  
                  
              });
      }
}
 
        }
    
        function _walleribox_image_with_data(data){
           
            if(fwpgsettings.fwpg_gallery=='Fancybox'){
                $.fancybox(data,{
                    'width':   'fwpg_frameWidth' in fwpgsettings ?  fwpgsettings.fwpg_frameWidth: "560" ,
                    'height':    'fwpg_frameHeight'in fwpgsettings? fwpgsettings.fwpg_frameHeight:"340" ,
                    'titleShow': 'fwpg_showTitle' in fwpgsettings? true:   false  ,
                    'cyclic': 'fwpg_cyclic' in fwpgsettings?   true :  false  ,
                    'titlePosition': 'fwpg_titlePosition'in fwpgsettings? fwpgsettings.fwpg_titlePosition:'inside',
                    'padding':   'fwpg_padding'in fwpgsettings? fwpgsettings.fwpg_padding:'10' ,
                    'autoScale':  'fwpg_imageScale' in fwpgsettings?   "true" :  "false"  ,
                    'padding':   'fwpg_padding' in fwpgsettings? fwpgsettings.fwpg_padding: "10",
                    'opacity':  'fwpg_Opacity' in fwpgsettings?   "true" :  "false"  ,
                    'speedIn':   'fwpg_SpeedIn' in fwpgsettings? fwpgsettings.fwpg_SpeedIn: "300",
                    'speedOut':  'fwpg_SpeedOut' in fwpgsettings?  fwpgsettings.fwpg_SpeedOut :"300",
                    'changeSpeed':    'fwpg_SpeedChange'in fwpgsettings?  fwpgsettings.fwpg_SpeedChange: "300",
                    'overlayShow':  'fwpg_overlayShow' in fwpgsettings?"true" :  "false"  ,
                    'overlayColor':   'fwpg_overlayColor'in fwpgsettings?  fwpgsettings.fwpg_overlayColor: '#666',
                    'overlayOpacity':   'fwpg_overlayOpacity'in fwpgsettings?  fwpgsettings.fwpg_overlayOpacity: "0.3",
                    'centerOnScroll':  'fwpg_centerOnScroll' in fwpgsettings?  "true" :  "false"  ,
                    'enableEscapeButton':  'fwpg_enableEscapeButton'in fwpgsettings?   "true"  : "false"  ,
                    'showCloseButton':  'fwpg_showCloseButton'in fwpgsettings?   "true" :  "false"  ,
                    'hideOnOverlayClick': false, //'fwpg_hideOnOverlayClick'in fwpgsettings?   "true" :   "false"  ,
                    'hideOnContentClick': false, //'fwpg_hideOnContentClick' in fwpgsettings?  "true" :  "false" , 
                    //'OnStart:':'fwpg_callbackOnStart' in fwpgsettings? fwpgsettings.fwpg_callbackOnStart:  null ,
                    //'OnComplete':'fwpg_callbackOnShow'in fwpgsettings? fwpgsettings.fwpg_callbackOnShow :null,
                    'OnClosed':'fwpg_callbackOnClose'in fwpgsettings? fwpgsettings.fwpg_callbackOnClose:null
                })
            }
            if(fwpgsettings.fwpg_gallery=='PrettyPhoto'){
   
                var images=new Array(),
                titles=new Array(),
                desc=new Array()
                $.each(data,function(i,x){
                    var t=typeof x.title !='undefined'?x.title:'';
                    images.push(x.href);
                    desc.push(t)
                })
   
                $.prettyPhoto.open(images,titles,desc);
            }
            if(fwpgsettings.fwpg_gallery=='Photoswipe'){
       var obs= $('.fp-WallPhotoThumb, a.fp-PhotoThumbnail, .fp-WallPhoto,.fp-WidgetPhoto');
      if(obs.length>0){
       $('.fp-WallPhotoThumb, a.fp-PhotoThumbnail, .fp-WallPhoto, .fp-WidgetPhoto').photoSwipe({ enableMouseWheel: false , enableKeyboard: false });
      }
}
 
        }
    
        
    function output_time(current_time,previous_time){

    var curtime=new Date().getTime();
    var oldtime=new Date(previous_time)
   
    var dif= curtime-oldtime.getTime()

    var string="";

    //if dif is less than min show seconds
    if(dif<=1000){
        string= ' '+fwpgsettings.abtsec
        }
    //if dif is less than min show seconds
    if(dif>1000 && dif<60000){
        string= Math.floor(dif/1000)+ ' '+fwpgsettings.seconds
        }
    //if btwn 1 & 2 min
    if(dif>=60000 && dif<120000){
        string=' '+fwpgsettings.abtmin
        }
    //if dif is less than hr show min
  
    if(dif>=120000 && dif<3600000){
        string= Math.floor(dif/1000/60)+ ' '+fwpgsettings.minutes
    }
    if(dif>=3600000 && dif<7200000){
        string=  ' '+fwpgsettings.abthr
    }
    //if dif is less than 1 day show hrs
    if(dif>=7200000&&dif<86400000){
        string= Math.floor(dif/1000/60/60)+ ' '+fwpgsettings.hours
        }
  
    //if greater than day but less than week in this year
    if(dif>=86400000 && dif<604800000){
        string=oldtime.toString('dddd')+' at ' + oldtime.toString('HH:mm')
    }
    //if greater than week but in this year
    if(dif>=604800000 && dif<31556952000){
        string=oldtime.toString('dd MMMM')+' at ' + oldtime.toString('HH:mm')
      
    }
    //if greater than year
    if(dif>31556952000){
        string=oldtime.toString('dd MMMM yyyy')+' at ' + oldtime.toString('HH:mm')
        }

    return string
 

}          
    })//close ready

};
// Load the SDK Asynchronously
(function(d){
    var js, id = 'facebook-jssdk';
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
}(document));
