'use strict';

GitLog.Application = React.createClass({
    render: function() {
        var NavigationBar = GitLog.NavigationBar;
        return (
            <NavigationBar/>
        );
    }
});

React.render(
    <GitLog.Application/>,
    document.body
);
