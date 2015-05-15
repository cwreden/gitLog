'use strict';

GitLog.ChangeLog = React.createClass({
    getInitialState: function() {
        return {
            data: []
        };
    },
    componentDidMount: function() {
        $.ajax({
            url: '/changelog/' + this.props.params.owner + '/' + this.props.params.repo + '/' + this.props.params.tag,
            dataType: 'json',
            cache: false,
            success: function(response) {
                this.setState(response);
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }.bind(this)
        });
    },
    render: function () {
        var Panel = ReactBootstrap.Panel;
        var ListGroup = ReactBootstrap.ListGroup;
        var title = 'ChangeLog for ' + this.props.params.owner + '/' + this.props.params.repo + '/' + this.props.params.tag;

        var nodes = this.state.data.map(function (data) {
            var ListGroupItem = ReactBootstrap.ListGroupItem;
            var sha = data.sha;
            return (
                <ListGroupItem key={sha} id={sha}>{data.message}</ListGroupItem>
            );
        });
        return (
            <Panel header={title}>
                <ListGroup className="changeLog" fill>
                    {nodes}
                </ListGroup>
            </Panel>
        );
    }
});
