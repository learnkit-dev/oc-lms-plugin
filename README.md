
# LearnKit LMS

This documentation is only for LearnKit agents.


## Documentation
### Custom code option for content blocks

#### Result for given course
`resultForCourse(courseId)` <br />
This function currently only works for H5P content blocks.

#### Result for given page
`resultForPage(pageId)` <br />
This function currently only works for H5P content blocks.

#### Result for given content block
`resultForBlock(pageId, blockHash)` <br />
This function currently only works for H5P content blocks.

### Delete results for user
#### Button in custom code
```html
<button
    data-request="onResetResults"
    data-request-data="type: 'page', id: pageId">Delete for this page</button>
```

## Authors

- [@SebastiaanKloos](https://www.github.com/sebastiaankloos)


## Used By

This project is used by the following companies:

- Codecycler
- LearnKit

## Support

For support, email support@codecycler.com, join our Discord server or contact Sebastiaan Kloos on Telegram ([@sebastiaan_codecycler](https://t.me/sebastiaan_codecycler))


## License

[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/)

  