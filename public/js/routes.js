'use strict';

var Router = ReactRouter;
var Route = Router.Route;

var routes = (
    <Route handler={App}>
        <Route path="about" handler={About}/>
        <Route path="inbox" handler={Inbox}/>
    </Route>
);
