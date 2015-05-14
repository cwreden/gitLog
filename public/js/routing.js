'use strict';

var Router = ReactRouter;
var Route = Router.Route;
var DefaultRoute = Router.DefaultRoute;
var NotFoundRoute = Router.NotFoundRoute;

var NotFound = React.createClass({
    render: function () {
        return <h2>Not Found!</h2>;
    }
});

var routes = (
    <Route handler={GitLog.Application}>
        <Route path="Dashboard" handler={GitLog.Dashboard}/>
        <DefaultRoute handler={GitLog.Dashboard}/>
        <NotFoundRoute handler={NotFound} />
    </Route>
);

Router.run(routes, Router.HashLocation, function(Root) {
    React.render(<Root/>, document.body);
});
