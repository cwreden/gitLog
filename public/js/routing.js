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
        <Route path=":owner" handler={GitLog.Profile}/>
        <Route path=":owner/:repo" handler={GitLog.Repository}/>
        <DefaultRoute handler={GitLog.Home}/>
        <NotFoundRoute handler={NotFound} />
    </Route>
);

Router.run(routes, Router.HashLocation, function(Root) {
    React.render(<Root/>, document.body);
});
