git filter-branch --commit-filter '
        if [ "$GIT_COMMITTER_NAME" = "Paolo Tresso" ];
        then
                GIT_COMMITTER_NAME="pixline";
                GIT_AUTHOR_NAME="pixline";
                GIT_COMMITTER_EMAIL="plugins@swergroup.com";
                GIT_AUTHOR_EMAIL="plugins@swergroup.com";
                git commit-tree "$@";
        else
                git commit-tree "$@";
        fi' HEAD

