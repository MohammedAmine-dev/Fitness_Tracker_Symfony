<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260523160000_CreateExercisesTables extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create exercises catalog and exercise logs tables.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE exercises (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, category VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql("INSERT INTO exercises (name, category, description) VALUES 
    ('Brisk Walk', 'cardio', 'Keep shoulders relaxed, swing arms lightly, and maintain a steady pace.'),
    ('Jogging', 'cardio', 'Land softly mid-foot, keep a tall posture, and breathe rhythmically.'),
    ('Cycling', 'cardio', 'Set the seat so knees stay slightly bent at the bottom of each pedal stroke.'),
    ('Jump Rope', 'cardio', 'Use small wrist circles, keep elbows close, and land on the balls of your feet.'),
    ('Rowing Machine', 'cardio', 'Drive with legs first, then lean back slightly and pull the handle to the ribs.'),
    ('Stair Climb', 'cardio', 'Step through the whole foot and keep a steady cadence.'),
    ('Bench Press', 'strength', 'Keep feet planted, lower the bar to mid-chest, and press with a steady path.'),
    ('Back Squat', 'strength', 'Brace the core, keep knees tracking toes, and maintain a neutral spine.'),
    ('Deadlift', 'strength', 'Hinge at the hips, keep the bar close, and lift with a flat back.'),
    ('Overhead Press', 'strength', 'Squeeze glutes, brace the core, and press the weight overhead without leaning back.'),
    ('Bent-Over Row', 'strength', 'Hinge forward, keep back flat, and pull elbows toward your hips.'),
    ('Kettlebell Swing', 'strength', 'Use a hip hinge, snap hips forward, and let the bell float chest high.'),
    ('Push-Up', 'calisthenics', 'Keep a straight line head to heels and lower chest to elbow height.'),
    ('Pull-Up', 'calisthenics', 'Start from a dead hang and pull chin above the bar without swinging.'),
    ('Dips', 'calisthenics', 'Keep shoulders down, elbows close, and lower until upper arms are parallel.'),
    ('Bodyweight Squat', 'calisthenics', 'Sit hips back, keep chest up, and push knees out over toes.'),
    ('Plank', 'calisthenics', 'Brace the core and keep hips level while holding a straight line.'),
    ('Mountain Climbers', 'calisthenics', 'Keep hips low and drive knees forward in a controlled rhythm.'),
    ('Soccer Dribble', 'sports', 'Use light touches, keep the ball close, and stay on the balls of your feet.'),
    ('Basketball Layups', 'sports', 'Approach on a controlled pace and finish softly off the glass.'),
    ('Tennis Rally', 'sports', 'Split step before contact and follow through toward the target.'),
    ('Badminton Clears', 'sports', 'Rotate the shoulders and finish the swing high and forward.'),
    ('Volleyball Pepper', 'sports', 'Stay low, keep a platform with forearms, and communicate with partners.'),
    ('Swimming Laps', 'sports', 'Keep a long body line and exhale steadily underwater.')
    ");
        $this->addSql('CREATE TABLE exercise_logs (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, exercise_id INT NOT NULL, duration INT NOT NULL, calories_burned INT NOT NULL, date DATE NOT NULL, INDEX IDX_EFBC3B68A76ED395 (user_id), INDEX IDX_EFBC3B682B3BA0E (exercise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercise_logs ADD CONSTRAINT FK_EFBC3B68A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exercise_logs ADD CONSTRAINT FK_EFBC3B682B3BA0E FOREIGN KEY (exercise_id) REFERENCES exercises (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE exercise_logs DROP FOREIGN KEY FK_EFBC3B68A76ED395');
        $this->addSql('ALTER TABLE exercise_logs DROP FOREIGN KEY FK_EFBC3B682B3BA0E');
        $this->addSql('DROP TABLE exercise_logs');
        $this->addSql('DROP TABLE exercises');
    }
}
