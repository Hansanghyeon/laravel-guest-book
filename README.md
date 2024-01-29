github oauth로 방명록 만들기

## Github OAuth 2.0 연결하기

- https://medium.com/@stdejan/mastering-laravel-a-step-by-step-github-login-tutorial-94c8a45900a8
- https://laravel.com/docs/10.x/socialite

## 댓글 만들기

### 모델, 컨트롤러, 마이그레이션 만들기

```sh
sail artisan make:model Comment -c -m
```

### DB 테이블 구성하기

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->string('userName');
            $table->string('commentStory');
            $table->timestamps();

            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
```

### 모델

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'userID',
        'userName',
        'commentStory',
    ];
}
```

### 컨트롤러

```php
<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'commentStory' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back();
        } else {
            Comment::create([
                'userID' => auth()->id,
                'userName' => Auth::user()->name,
                'commentStory' => request()->commentStory,
            ]);
            return redirect()->back();
        }
    }
}
```

`$validator`는 유효성 검사로 조건을 넣고 if문을 사용해 에러 발생시 아무것도 하지 않고 전 페이지로 넘어가게 한다. 에러가 없을 시에는 댓글을 작성하고 전 페이지로 이동.

`parent_id` `commentStory`는 input으로 값을 받아온다.
`userID` `userName`은 현재 사용자의 id와 이름을 값으로 받는다.

### `web.php`

`web.php` 파일을 열어 아래와 같이 입력해줍니다.

```php
Route::post('/comments/store', [CommentController::class, 'store'])->name('comments.add');
```

뒤에 name은 라우트에 이름을 부여한 것입니다.

### 댓글 작성하기

`resources/views/boards/show.blade.php` 파일을 생성합니다.

```blade
{{-- 댓글작성 --}}
@auth()
  <div class="w-4/5 mx-auto mt-6 text-right">
    <form method="post" action="{{ route('comment.add') }}">
      @csrf
      <textarea name="commentStory" class="border border-blue-300 resize-none w-full h-32"></textarea>
      <input type="submit" value="작성" class="mt-4 px-4 py-1 bg-gray-500 hover:bg-gray-700 text-gray-200">
    </form>
  </div>
@endauth
```

`@auth()`를 이용해 로그인했을 경우에만 댓글 작성창이 보이게 만들었습니다.

form action은 `web.php`에서 부여한 이름을 넣어줍니다.
