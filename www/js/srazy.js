
for(var i in srazyEvents) {
    addGeneral(i);
    addNext(i);
}

function addGeneral(url) {
    $.getJSON( 'http://api.srazy.info/event/' + url, {
        format: "json"
    }).done( function(data) {
            $('#title-' + url).append('<a href="http://srazy.info/' + data.title + '/">' +  data.title + '</a>');
            $('#desc-' + url ).append(data.eventDesc);
            $('#adminName-' + url).append(data.adminName);
        });
}

function addNext(url) {
    $.getJSON( 'http://api.srazy.info/event/' + url + '/current').done( function(data) {

           console.log('Zpracovávám nejbližší termín: ' + url + ' Data: ');
           console.log(data);

           var now = new Date();
           var start = new Date(data.start);
           if(start > now) {
                console.log('Datum ' + start + ' > ' + now + ', tedy zpracovávám.');
                $('#current-' + url).html(
                    '<dl><dt>Datum:</dt><dd>'+
                    start.toLocaleDateString()+
                    '</dd><dt>Čas:</dt><dd>'+
                    start.toLocaleTimeString()+
                   '</dd><dt>Místo:</dt><dd><strong>'+
                    data.venueName+ '</strong>, ' +
                    data.venueAddress +
                   '</dd><dt>Mapa:</dt>'+
                    '<dd><br /><div  class="span5" style="height: 300px" id="map-' + url + '"></div></dd></dl>'
                );
               var map = L.map('map-' + url).setView([ data.venueLat, data.venueLong], 13);

               var marker = L.marker([data.venueLat, data.venueLong]).addTo(map);
               marker.bindPopup(data.venueName).openPopup();
               L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png').addTo(map);
           }
    });
}