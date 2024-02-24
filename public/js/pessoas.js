$(document).ready(function () {
    $.get( $('#url').val(), function( data ) {
        let array = getOnlyNames(data)
        $('#nome').autocomplete({
            source: array
        })
    });
});

function getOnlyNames(fullArray) {
    let array = [];
    fullArray.forEach((item) =>{
        array.push(item.nome)
    });
    return array;
}




