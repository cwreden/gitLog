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
        <Route path="Home" handler={GitLog.Home}/>
        <Route path="Dashboard" handler={GitLog.Dashboard}/>
        <Route path="Users/:owner" handler={GitLog.Profile}/>
        <Route path="Repos/:owner/:repo" handler={GitLog.Repository}/>
        <Route path="ChangeLog/:owner/:repo/:tag" handler={GitLog.ChangeLog}/>
        <DefaultRoute handler={GitLog.Home}/>
        <NotFoundRoute handler={NotFound}/>
    </Route>
);

Router.run(routes, Router.HashLocation, function(Root) {
    React.render(<Root/>, document.body);
});
