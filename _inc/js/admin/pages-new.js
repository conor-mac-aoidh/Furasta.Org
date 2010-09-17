$(document).ready(function(){
        var type=$("#pages-type-content").attr("type");
        $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");
        loadPageType(type,0);

        var parent=$("#select-parent :selected").text();
        if(parent!="---"){
                var url=$("#slug-put").val();
                url=url+"/"+parent.replace(/\s/g,"-")+"/";
                $("#slug-url").text(url);
                $("#slug-put").attr("value",url);
                $("#slug-url").attr("href",url);
        }

        $("#edit-save").click(function(){
                var errors=required(["Name"]);
                if(errors==0)
                        errors+=pattern(["Name"]);
                if(errors!=0)
                        return false;
        });
        $("#options-link").click(displayOptions);
        $("#edit-type").change(function(){
                $("#pages-type-content").html("Loading... <img src=\"/_inc/img/loading.gif\"/>");
                var type=$(this).attr("value");
                loadPageType(type,0);
        });
        $("#pages-permissions").click(function(){ pagePermissions(id); });
        $("#page-name").keyup(function(){
                var result=slugCheck($("#page-name").attr("value"));
                if(result==false)
                        $("#page-name").addClass("error");
                else{
                        var parent=$("#select-parent :selected").text();
                        var host="http://"+window.location.hostname+"/";
                        $("#page-name").removeClass("error");
                        var fullUrl=(parent=="---")?host+result:host+parent.replace(/\s/g,"-")+"/"+result;
                        $("#slug-url").html(fullUrl);
                        $("#slug-url").attr("href",fullUrl);
                        $("#slug-put").attr("value",result);
                }
        });
});
