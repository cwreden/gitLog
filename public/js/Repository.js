'use strict';

GitLog.Repository = React.createClass({
    getInitialState: function() {
        return {
            selectedGitTag: null
        };
    },
    render: function () {
        var Grid = ReactBootstrap.Grid;
        var Row = ReactBootstrap.Row;
        var Col = ReactBootstrap.Col;
        var GitTagList = GitLog.GitTagList;
        var ChangeLog = GitLog.ChangeLog;
        return (
            <div className="repository">
                <h2>{this.props.params.owner}/{this.props.params.repo}</h2>
                <Grid>
                    <Row className='show-grid'>
                        <Col xs={6} md={4}><GitTagList owner={this.props.params.owner} repo={this.props.params.repo} handleSelect={this.handleGitTagSelect} selectedGitTag={this.state.selectedGitTag}/></Col>
                        <Col xs={12} md={8}><ChangeLog owner={this.props.params.owner} repo={this.props.params.repo} gitTag={this.state.selectedGitTag}/></Col>
                    </Row>
                </Grid>
            </div>
        );
    },
    handleGitTagSelect: function (name, sha, record) {
        this.setState({
            selectedGitTag: record
        });
    }
});
