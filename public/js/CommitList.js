'use strict';

GitLog.CommitList = React.createClass({
    getInitialState: function() {
        return {
            data: []
        };
    },
    componentWillReceiveProps: function(props) {
        this.setState({data: []});

        $.ajax({
            url: '/repos/' + props.owner + '/' + props.repo + '/tags/' + props.gitTag.name + '/commits',
            dataType: 'json',
            cache: false,
            success: function(response) {
                this.setState({data: response.commits});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(status, err.toString());
            }.bind(this)
        });
    },
    render: function () {
        var Panel = ReactBootstrap.Panel;
        var Button = ReactBootstrap.Button;
        var ListGroup = ReactBootstrap.ListGroup;
        var title = 'Commits';

        if (jQuery.isPlainObject(this.props.gitTag)) {
            title = 'Commits for Tag ' + this.props.gitTag.name;
        }

        var nodes = this.state.data.map(function (data) {
            var ListGroupItem = ReactBootstrap.ListGroupItem;
            var sha = data.sha;
            return (
                <ListGroupItem key={sha} id={sha}>{data.message}</ListGroupItem>
            );
        });

        var ChangeLogButton = <Button bsStyle='primary' disabled>Create ChangeLog</Button>;
        if (nodes.length > 0) {
            let props = this.props;
            let ChangeLogLink = '#/changelog/' + props.owner + '/' + props.repo + '/' + props.gitTag.name;
            ChangeLogButton = <Button bsStyle='primary' href={ChangeLogLink}>Create ChangeLog</Button>;
        }

        return (
            <Panel header={title}>
                {ChangeLogButton}
                <ListGroup className="commitList" fill>
                    {nodes}
                </ListGroup>
            </Panel>
        );
    }
});
