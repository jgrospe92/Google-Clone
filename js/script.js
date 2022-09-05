const query = "https://suggestqueries.google.com/complete/search?client=chrome&q=";
const searchQuery = "https://www.google.ca/search?q="


$("#searchQuery").keydown(function(event){
    if(event.code === 'Enter'){
        searchWithVal($("#searchQuery").val());
    }
})


$("#searchQuery").on("input", function() {
    const value = $(this).val();
    if(value==="") {
        $(".suggestions").empty();
        $("#search").css("border-radius","3%/50%");
        return;
    } 
    $.ajax({
        url:query+''+value, 
        dataType: 'jsonp',
        success: function(result) {
            $(".suggestions").empty();
            $("#search").css({"border-bottom-left-radius":"0","border-bottom-right-radius":"0"});
            $.each(result[1], function(key, val) {
                const newDiv = document.createElement("div");
                newDiv.className="suggestionDiv";
                const newSpan = document.createElement("span");
                newSpan.textContent = val;
                newDiv.appendChild(newSpan);
                newDiv.addEventListener("click", function() {
                    searchWithVal(this.children[0].textContent);
                });
                $(".suggestions").append(newDiv);
            })
        }
    })
})

function searchWithVal(val) {
    const newVal = val.replace(' ','+');
    window.open(searchQuery+''+newVal,'_blank').focus();
}