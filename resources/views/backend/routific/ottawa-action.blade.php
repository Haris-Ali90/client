<button class='details green-gradient btn' data-route-id="{{$routes->route_id}}" data-joey-id="{{$routes->joey_id}}" title='Details'>D</button>
<a class=' orange-gradient btn' target='_blank' href='{{backend_url("returnroute/".$routes->route_id."/edit/hub/19")}}' title='Edit Route'>E</a>
<button class='transfer  black-gradient btn' data-route-id="{{$routes->route_id}}" title='Transfer'>T</button>
<button type='button' class=' red-gradient btn' data-toggle='modal' data-target='#ex5' onclick='initialize("{{$routes->joey_id}}","{{$routes->route_id}}")' title='Map of Whole Route'>M</button>
<button type='button' class=' orange-gradient btn' data-toggle='modal' data-target='#ex5' onclick='currentMap("{{$routes->route_id}}")' title='Map of Current Route'>CM</button>
<button type='button'  class='delete  red-gradient btn'  data-id="{{$routes->route_id}}" title='Delete Route'>R</button>
<button class='reroute  orange-gradient btn' data-re="{{$routes->route_id}}"  title='Re Route'>RR</button>
<a class=' orange-gradient btn' target='_blank' href='{{backend_url("routific/route/".$routes->route_id."/history")}}' title='Route History'>RH</a>