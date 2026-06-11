import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';

import AppLayout from '@/layouts/app-layout';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';

interface Post {
    id?: number;
    title: string;
    content: string;
    is_draft: boolean;
    published_at: string | null;
}

interface Props {
    post: Post | null;
    mode: 'create' | 'edit';
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posting',
        href: '/admin/posts',
    },
];

export default function Upsert({ post, mode }: Props) {
    const {
        data,
        setData,
        post: submitPost,
        put,
        processing,
        errors,
    } = useForm({
        title: post?.title ?? '',
        content: post?.content ?? '',
        is_draft: post?.is_draft ?? true,
        published_at: post?.published_at ? new Date(post.published_at).toISOString().slice(0, 16) : '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        if (mode === 'create') {
            submitPost(route('admin.posts.store'));
        } else {
            put(route('admin.posts.update', post?.id));
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={mode === 'create' ? 'Create Post' : 'Edit Post'} />

            <div className="p-6">
                <h1 className="mb-6 text-2xl font-bold">{mode === 'create' ? 'Create Post' : 'Edit Post'}</h1>

                <form onSubmit={submit} className="space-y-6">
                    <div>
                        <Label htmlFor="title">Title</Label>

                        <Input id="title" value={data.title} onChange={(e) => setData('title', e.target.value)} />

                        <InputError message={errors.title} />
                    </div>

                    <div>
                        <Label htmlFor="content">Content</Label>

                        <textarea
                            id="content"
                            className="w-full rounded-md border p-3"
                            value={data.content}
                            onChange={(e) => setData('content', e.target.value)}
                        />

                        <InputError message={errors.content} />
                    </div>

                    <div>
                        <Label htmlFor="published_at">Publish At</Label>

                        <Input
                            id="published_at"
                            type="datetime-local"
                            value={data.published_at ?? ''}
                            onChange={(e) => setData('published_at', e.target.value)}
                        />
                    </div>

                    <div className="flex items-center gap-2">
                        <input id="is_draft" type="checkbox" checked={data.is_draft} onChange={(e) => setData('is_draft', e.target.checked)} />

                        <Label htmlFor="is_draft">Draft</Label>
                    </div>

                    <Button disabled={processing}>{mode === 'create' ? 'Create' : 'Update'}</Button>
                </form>
            </div>
        </AppLayout>
    );
}
