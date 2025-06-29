git fetch --tags
LATEST_TAG=$(git describe --tags $(git rev-list --tags --max-count=1))
git checkout tags/$LATEST_TAG -b release
git pull origin release

sed -i "s/^BUGSNAG_API_KEY=.*/BUGSNAG_API_KEY=f75b3bf691ff27f2d77053c946fef9fe/" .env
