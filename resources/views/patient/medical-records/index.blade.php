<!-- resources/views/patient/medical-records/index.blade.php -->
@extends('layouts.patient')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">My Medical Records</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6">
            @forelse($records as $record)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                    <!-- Header Section -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-900">
                                        <a href="{{ route('patient.doctors.show', $record->doctor->doctor_id) }}"
                                            class="hover:text-blue-600 transition-colors duration-200">
                                            Dr. {{ $record->doctor->user->username }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ $record->treatment_date->format('F d, Y - h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium
                            {{ $record->status === 'COMPLETED'
                                ? 'bg-green-100 text-green-800'
                                : ($record->status === 'IN_PROGRESS'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : 'bg-gray-100 text-gray-800') }}">
                                {{ $record->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-6 space-y-6">
                        <!-- Symptoms Section -->
                        @if ($record->symptoms)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Symptoms
                                </h4>
                                <p class="text-gray-600">{{ $record->symptoms }}</p>
                            </div>
                        @endif

                        <!-- Diagnosis Section -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                Diagnosis
                            </h4>
                            <p class="text-gray-600">{{ $record->diagnosis }}</p>
                        </div>

                        <!-- Medical Action Section -->
                        @if ($record->medical_action)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    Medical Action
                                </h4>
                                <p class="text-gray-600">{{ $record->medical_action }}</p>
                            </div>
                        @endif

                        <!-- Prescriptions Section -->
                        @if ($record->medicines->count() > 0)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                        </path>
                                    </svg>
                                    Prescribed Medicines
                                </h4>
                                <div class="space-y-3">
                                    @foreach ($record->medicines as $medicine)
                                        <div class="flex justify-between items-start p-3 bg-white rounded shadow-sm">
                                            <div>
                                                <h5 class="font-medium text-gray-900">{{ $medicine->name }}</h5>
                                                <p class="text-sm text-gray-500">{{ $medicine->pivot->dosage }}</p>
                                                @if ($medicine->pivot->instructions)
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        {{ $medicine->pivot->instructions }}</p>
                                                @endif
                                            </div>
                                            <span class="text-sm font-medium text-gray-600">Qty:
                                                {{ $medicine->pivot->quantity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Notes Section -->
                        @if ($record->notes)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Additional Notes
                                </h4>
                                <p class="text-gray-600">{{ $record->notes }}</p>
                            </div>
                        @endif

                        @if ($record->feedback)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Your Feedback
                                </h4>
                                <!-- Star Rating Display -->
                                <div class="flex items-center mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $record->feedback->overall_rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">
                                        Submitted {{ $record->feedback->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-gray-600">{{ $record->feedback->review }}</p>

                                @if ($record->feedback->doctor_response)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h5 class="text-sm font-medium text-gray-700 mb-1">Doctor's Response:</h5>
                                        <p class="text-gray-600">{{ $record->feedback->doctor_response }}</p>
                                        <span class="text-sm text-gray-500">
                                            Responded {{ $record->feedback->updated_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @elseif($record->status === 'COMPLETED')
                            <button onclick="openFeedbackModal({{ $record->record_id }})"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Add Feedback
                            </button>
                        @endif
                    </div>

                    <!-- Footer Section -->
                    @if ($record->follow_up_date)
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                            <p class="text-sm text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Follow-up Date: <span
                                    class="font-medium ml-1">{{ $record->follow_up_date->format('F d, Y') }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-lg shadow">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No medical records</h3>
                    <p class="mt-1 text-sm text-gray-500">You don't have any medical records yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $records->links() }}
        </div>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-6 w-96">
            <h2 class="text-xl font-semibold mb-4">Submit Feedback</h2>
            <!-- Feedback Form -->
            <form id="feedbackForm">
                @csrf
                <input type="hidden" id="record_id" name="record_id">

                <!-- Star Rating -->
                <div class="flex items-center mb-4">
                    <input type="hidden" id="overall_rating" name="overall_rating" value="0">
                    <div class="flex">
                        <span class="rating-star text-gray-300 cursor-pointer" onclick="setRating(1)">&#9733;</span>
                        <span class="rating-star text-gray-300 cursor-pointer" onclick="setRating(2)">&#9733;</span>
                        <span class="rating-star text-gray-300 cursor-pointer" onclick="setRating(3)">&#9733;</span>
                        <span class="rating-star text-gray-300 cursor-pointer" onclick="setRating(4)">&#9733;</span>
                        <span class="rating-star text-gray-300 cursor-pointer" onclick="setRating(5)">&#9733;</span>
                    </div>
                </div>

                <!-- Category Field -->
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category" name="category" class="mt-1 block w-full border-gray-300 rounded-md">
                        <option value="">Select Category</option>
                        <option value="GENERAL">General</option>
                        <option value="DOCTOR_SERVICE">Doctor Service</option>
                        <option value="FACILITY">Facility</option>
                        <option value="STAFF_SERVICE">Staff Service</option>
                        <option value="WAIT_TIME">Wait Time</option>
                        <option value="TREATMENT_QUALITY">Treatment Quality</option>
                        <option value="COMMUNICATION">Communication</option>
                    </select>
                </div>

                <!-- Review Textarea -->
                <div class="mb-4">
                    <label for="review" class="block text-sm font-medium text-gray-700">Review</label>
                    <textarea id="review" name="review" rows="4" class="mt-1 block w-full border-gray-300 rounded-md"></textarea>
                </div>

                <!-- Error Messages -->
                <div id="feedbackErrors"
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative hidden mb-4">
                    <ul id="errorList"></ul>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeFeedbackModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openFeedbackModal(recordId) {
            document.getElementById('record_id').value = recordId;
            document.getElementById('feedbackModal').classList.remove('hidden');
            resetForm();
        }

        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
            resetForm();
        }

        function resetForm() {
            const form = document.getElementById('feedbackForm');
            form.reset();
            document.getElementById('overall_rating').value = '0';
            document.querySelectorAll('.rating-star').forEach(star => {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            });
            document.getElementById('feedbackErrors').classList.add('hidden');
            document.getElementById('errorList').innerHTML = '';
        }

        function setRating(rating) {
            document.getElementById('overall_rating').value = rating;
            const stars = document.querySelectorAll('.rating-star');
            stars.forEach((star, index) => {
                star.classList.toggle('text-yellow-400', index < rating);
                star.classList.toggle('text-gray-300', index >= rating);
            });
        }


        document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Get form elements
            const form = this;
            const submitButton = form.querySelector('button[type="submit"]');
            const recordId = document.getElementById('record_id').value;

            // Clear previous errors
            document.getElementById('feedbackErrors').classList.add('hidden');
            document.getElementById('errorList').innerHTML = '';

            // Client-side validation
            const rating = document.getElementById('overall_rating').value;
            const category = document.getElementById('category').value;
            const review = document.getElementById('review').value;
            const errors = [];

            if (!rating || rating === '0') {
                errors.push('Please select a rating');
            }
            if (!category) {
                errors.push('Please select a category');
            }
            if (!review || review.trim().length < 10) {
                errors.push('Please enter a review with at least 10 characters');
            }

            if (errors.length > 0) {
                displayErrors(errors);
                return;
            }

            try {
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="loading">Submitting...</span>';

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Create formData object
                const formData = {
                    record_id: recordId,
                    overall_rating: rating,
                    category: category,
                    review: review,
                    _token: csrfToken
                };

                console.log('Submitting data:', formData); // Debug log

                const response = await fetch(`/patient/medical-records/${recordId}/feedback`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                console.log('Response:', data); // Debug log

                if (!response.ok) {
                    throw new Error(data.message || 'Server error');
                }

                if (data.success) {
                    showSuccessMessage('Feedback submitted successfully');
                    closeFeedbackModal();
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    throw new Error(data.message || 'Error submitting feedback');
                }

            } catch (error) {
                console.error('Error details:', error);
                displayErrors([error.message]);
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Submit';
            }
        });


        function displayErrors(errors) {
    const errorsContainer = document.getElementById('feedbackErrors');
    const errorsList = document.getElementById('errorList');

    errorsList.innerHTML = '';
    errors.forEach(error => {
        const li = document.createElement('li');
        li.className = 'mb-1 list-disc ml-4';
        li.textContent = error;
        errorsList.appendChild(li);
    });

    errorsContainer.classList.remove('hidden');
    errorsContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

        
function showSuccessMessage(message) {
    const successMessage = document.createElement('div');
    successMessage.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r fixed top-4 right-4 z-50';
    successMessage.textContent = message;
    document.body.appendChild(successMessage);
    setTimeout(() => successMessage.remove(), 3000);
}

function openFeedbackModal(recordId) {
    document.getElementById('record_id').value = recordId;
    document.getElementById('feedbackModal').classList.remove('hidden');
    resetForm();
}

function closeFeedbackModal() {
    document.getElementById('feedbackModal').classList.add('hidden');
    resetForm();
}

function resetForm() {
    const form = document.getElementById('feedbackForm');
    form.reset();
    document.getElementById('overall_rating').value = '0';
    document.querySelectorAll('.rating-star').forEach(star => {
        star.classList.remove('text-yellow-400');
        star.classList.add('text-gray-300');
    });
    document.getElementById('feedbackErrors').classList.add('hidden');
    document.getElementById('errorList').innerHTML = '';
}

function setRating(rating) {
    document.getElementById('overall_rating').value = rating;
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        star.classList.toggle('text-yellow-400', index < rating);
        star.classList.toggle('text-gray-300', index >= rating);
    });
}

// Modal event listeners
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeFeedbackModal();
});

document.getElementById('feedbackModal').addEventListener('click', function(e) {
    if (e.target === this) closeFeedbackModal();
});
    </script>

@endsection
