<h1>مرحبا {{ $test->user->name }}</h1>
<p>هذه رسالة بخصوص اختبارك رقم: {{ $test->test_number }}</p>
<p>ملاحظات: {{ $note }}</p>
<p>نتيجة الاختبار: {{ $test->ai_result }}</p>
