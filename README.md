# CloudCompli PHP API

This is a sample application that demonstrates how to authenticate against cloudcompli.com via OAuth 2 and then call the CloudCompli API v2. Additionally, it may be deployed as documentation of the CloudCompli API V2.

## Installation

#### Configuration

Set up the URL to a cloudcompli.com instance in `example/shared/config.php`.

#### Database

Go to the cloudcompli.com instance you're connecting to and add two documents to its database.

Firstly, add this document to `oauth_clients`:

```
{
    "_id" : ObjectId("563ba09dfd27820b5bd28e58"),
    "id" : "demoapp",
    "secret" : "demopass",
    "name" : "demo"
}
```

And then add a document of this form to `oauth_client_endpoints`, substituting your endpoint URL in place of the example here.

```
{
    "_id" : ObjectId("563ba1f1fd27820b5bd28e5a"),
    "client_id" : "demoapp",
    "redirect_uri" : "http://localhost/cloudcompli-api-php/example/basic.php"
}
```

## License

Copyright 2015 CloudCompli, Inc.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

> http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.